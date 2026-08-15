<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$srcRoot = $root . DIRECTORY_SEPARATOR . 'src';

$producerScopes = [
    'src/Service/Locator/Address',
    'src/ServiceInterface/Locator/Address',
];

$rootCandidatePatterns = [
    'src/Service/Locator' => '/Address/',
    'src/ServiceInterface/Locator' => '/Address|Suggest|Ranker|Normalizer/',
];

$summary = [
    'legacy_symbols' => 0,
    'referencing_files' => 0,
    'total_references' => 0,
    'references_from_app_namespace' => 0,
    'references_from_smartresponsor_namespace' => 0,
    'references_from_config_or_other' => 0,
    'rename_sensitive_symbols' => 0,
];
$warnings = [];
$violations = [];
$symbols = [];
$references = [];

function lc16_path_join(string ...$parts): string
{
    return implode(DIRECTORY_SEPARATOR, $parts);
}

function lc16_relative(string $root, string $path): string
{
    return str_replace('\\', '/', substr($path, strlen($root) + 1));
}

function lc16_extract_namespace(string $code): ?string
{
    if (preg_match('/^namespace\s+([^;]+);/m', $code, $m)) {
        return trim($m[1]);
    }

    return null;
}

function lc16_extract_symbol_kind(string $code, string $declaredName): ?string
{
    if (preg_match('/^\s*(?:final\s+|abstract\s+)?class\s+' . preg_quote($declaredName, '/') . '\b/m', $code)) {
        return 'class';
    }
    if (preg_match('/^\s*interface\s+' . preg_quote($declaredName, '/') . '\b/m', $code)) {
        return 'interface';
    }
    if (preg_match('/^\s*trait\s+' . preg_quote($declaredName, '/') . '\b/m', $code)) {
        return 'trait';
    }

    return null;
}

function lc16_is_rename_sensitive(string $nameEntity, ?string $kind): bool
{
    if ($kind === 'interface') {
        return !str_ends_with($nameEntity, 'Interface');
    }

    foreach (['Service', 'Capability', 'Factory', 'Guard', 'Policy', 'Router', 'Ranker', 'Normalizer', 'Provider', 'Reader', 'Writer', 'Resolver', 'Mapper', 'Manager', 'Decorator'] as $suffix) {
        if (str_ends_with($nameEntity, $suffix)) {
            return false;
        }
    }

    return true;
}

function lc16_collect_php_files(string $directory): array
{
    if (!is_dir($directory)) {
        return [];
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if ($file instanceof SplFileInfo && $file->getExtension() === 'php') {
            $files[] = $file->getPathname();
        }
    }
    sort($files);

    return $files;
}

foreach ($producerScopes as $scope) {
    $directory = lc16_path_join($root, str_replace('/', DIRECTORY_SEPARATOR, $scope));
    foreach (lc16_collect_php_files($directory) as $path) {
        $code = (string) file_get_contents($path);
        $nameEntity = pathinfo($path, PATHINFO_FILENAME);
        $namespace = lc16_extract_namespace($code);
        $kind = lc16_extract_symbol_kind($code, $nameEntity);
        if ($namespace === null) {
            $warnings[] = sprintf('LC-16 producer has no namespace: %s', lc16_relative($root, $path));
            continue;
        }
        $fqn = $namespace . '\\' . $nameEntity;
        $symbols[$fqn] = [
            'fqn' => $fqn,
            'nameEntity' => $nameEntity,
            'kind' => $kind,
            'path' => lc16_relative($root, $path),
            'namespace' => $namespace,
            'rename_sensitive' => lc16_is_rename_sensitive($nameEntity, $kind),
            'scope' => $scope,
        ];
    }
}

foreach ($rootCandidatePatterns as $scope => $pattern) {
    $directory = lc16_path_join($root, str_replace('/', DIRECTORY_SEPARATOR, $scope));
    if (!is_dir($directory)) {
        continue;
    }
    foreach (glob($directory . DIRECTORY_SEPARATOR . '*.php') ?: [] as $path) {
        $nameEntity = pathinfo($path, PATHINFO_FILENAME);
        if (preg_match($pattern, $nameEntity) !== 1) {
            continue;
        }
        $code = (string) file_get_contents($path);
        $namespace = lc16_extract_namespace($code);
        $kind = lc16_extract_symbol_kind($code, $nameEntity);
        if ($namespace === null) {
            $warnings[] = sprintf('LC-16 root candidate has no namespace: %s', lc16_relative($root, $path));
            continue;
        }
        $fqn = $namespace . '\\' . $nameEntity;
        $symbols[$fqn] = [
            'fqn' => $fqn,
            'nameEntity' => $nameEntity,
            'kind' => $kind,
            'path' => lc16_relative($root, $path),
            'namespace' => $namespace,
            'rename_sensitive' => lc16_is_rename_sensitive($nameEntity, $kind),
            'scope' => $scope,
        ];
    }
}

ksort($symbols);
$summary['legacy_symbols'] = count($symbols);
foreach ($symbols as $symbol) {
    if ($symbol['rename_sensitive']) {
        ++$summary['rename_sensitive_symbols'];
    }
}

$allPhp = lc16_collect_php_files($srcRoot);
$referenceFileSet = [];
foreach ($allPhp as $path) {
    $relative = lc16_relative($root, $path);
    $code = (string) file_get_contents($path);
    $fileNamespace = lc16_extract_namespace($code);

    foreach ($symbols as $fqn => $symbol) {
        if ($relative === $symbol['path']) {
            continue;
        }

        $short = $symbol['nameEntity'];
        $escapedFqn = preg_quote($fqn, '/');
        $escapedShort = preg_quote($short, '/');
        $matches = [];

        if (preg_match('/^use\s+' . $escapedFqn . '(?:\s+as\s+[^;]+)?;/m', $code) === 1) {
            $matches[] = 'use-fqn';
        }
        if (preg_match('/' . $escapedFqn . '\b/', $code) === 1) {
            $matches[] = 'inline-fqn';
        }
        if (preg_match('/\b' . $escapedShort . '\b/', $code) === 1 && preg_match('/^use\s+.*\\' . $escapedShort . '(?:\s+as\s+[^;]+)?;/m', $code) === 1) {
            $matches[] = 'short-imported-nameEntity';
        }

        if ($matches === []) {
            continue;
        }

        $referenceFileSet[$relative] = true;
        $origin = 'other';
        if ($fileNamespace !== null && str_starts_with($fileNamespace, 'App\Locating\\')) {
            $origin = 'app';
            ++$summary['references_from_app_namespace'];
        } elseif ($fileNamespace !== null && str_starts_with($fileNamespace, 'Smartresponsor\\')) {
            $origin = 'smartresponsor';
            ++$summary['references_from_smartresponsor_namespace'];
        } else {
            ++$summary['references_from_config_or_other'];
        }

        $references[] = [
            'referencing_path' => $relative,
            'referencing_namespace' => $fileNamespace,
            'origin' => $origin,
            'referenced_symbol' => $fqn,
            'referenced_path' => $symbol['path'],
            'match_modes' => implode('|', array_values(array_unique($matches))),
            'rename_sensitive' => $symbol['rename_sensitive'],
        ];
        ++$summary['total_references'];
    }
}

$summary['referencing_files'] = count($referenceFileSet);
usort($references, static fn (array $a, array $b): int => [$a['referencing_path'], $a['referenced_symbol']] <=> [$b['referencing_path'], $b['referenced_symbol']]);

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-reference-audit-latest.json',
    json_encode([
        'component' => 'Locating',
        'wave' => 'LC-16',
        'scope' => 'Reference audit for address-focused Smartresponsor Locator legacy symbols',
        'summary' => $summary,
        'warnings' => $warnings,
        'violations' => $violations,
        'symbols' => array_values($symbols),
        'references' => $references,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$csv = fopen($reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-reference-audit-latest.csv', 'wb');
fputcsv($csv, ['referencing_path', 'referencing_namespace', 'origin', 'referenced_symbol', 'referenced_path', 'match_modes', 'rename_sensitive'], ',', '"', '');
foreach ($references as $reference) {
    fputcsv($csv, [
        $reference['referencing_path'],
        $reference['referencing_namespace'] ?? '',
        $reference['origin'],
        $reference['referenced_symbol'],
        $reference['referenced_path'],
        $reference['match_modes'],
        $reference['rename_sensitive'] ? 'yes' : 'no',
    ], ',', '"', '');
}
fclose($csv);

$md = [];
$md[] = '# Locating LC-16 service locator address reference map';
$md[] = '';
$md[] = 'This generated map identifies files that reference address-focused `App\Locating\\Service\\Locator` symbols before any physical move or namespace retirement.';
$md[] = '';
$md[] = sprintf('- Legacy symbols: `%d`', $summary['legacy_symbols']);
$md[] = sprintf('- Referencing files: `%d`', $summary['referencing_files']);
$md[] = sprintf('- Total references: `%d`', $summary['total_references']);
$md[] = sprintf('- Rename-sensitive symbols: `%d`', $summary['rename_sensitive_symbols']);
$md[] = '';
$md[] = '| Referencing file | Referenced symbol | Match modes | Rename sensitive |';
$md[] = '| --- | --- | --- | --- |';
foreach ($references as $reference) {
    $md[] = sprintf(
        '| `%s` | `%s` | `%s` | `%s` |',
        $reference['referencing_path'],
        $reference['referenced_symbol'],
        $reference['match_modes'],
        $reference['rename_sensitive'] ? 'yes' : 'no'
    );
}
if ($references === []) {
    $md[] = '| - | - | - | No references found. |';
}
$md[] = '';
file_put_contents($reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-reference-map.md', implode(PHP_EOL, $md));

if ($violations !== []) {
    fwrite(STDERR, "Locating LC-16 service locator address reference canon failed:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, ' - ' . $violation . "\n");
    }
    exit(1);
}

echo sprintf(
    "Locating LC-16 address reference audit completed: %d symbols, %d referencing files, %d references.\n",
    $summary['legacy_symbols'],
    $summary['referencing_files'],
    $summary['total_references']
);
if ($warnings !== []) {
    echo sprintf("Warnings recorded: %d. See report/locating-service-locator-address-reference-audit-latest.json.\n", count($warnings));
}
