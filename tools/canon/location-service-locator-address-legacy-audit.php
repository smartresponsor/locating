<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

$scopes = [
    'service_address_nested' => [
        'dir' => 'src/Service/Locator/Address',
        'namespace_prefix' => 'App\Locating\\Service\\Provider\\Location\\Runtime\\Address',
        'expected_kind' => 'class',
        'target_prefix' => 'src/Service/Address/Location/Legacy',
        'target_namespace_prefix' => 'App\Locating\\Service\\Address\\Location\\Legacy',
    ],
    'service_address_root' => [
        'dir' => 'src/Service/Locator',
        'namespace_prefix' => 'App\Locating\\Service\\Locator',
        'expected_kind' => 'class',
        'target_prefix' => 'src/Service/Address/Location/Legacy',
        'target_namespace_prefix' => 'App\Locating\\Service\\Address\\Location\\Legacy',
        'root_name_pattern' => '/Address/',
    ],
    'service_interface_address_nested' => [
        'dir' => 'src/ServiceInterface/Locator/Address',
        'namespace_prefix' => 'App\Locating\\ServiceInterface\\Provider\\Location\\Runtime\\Address',
        'expected_kind' => 'interface',
        'target_prefix' => 'src/ServiceInterface/Address/Location/Legacy',
        'target_namespace_prefix' => 'App\Locating\\ServiceInterface\\Address\\Location\\Legacy',
    ],
    'service_interface_address_root' => [
        'dir' => 'src/ServiceInterface/Locator',
        'namespace_prefix' => 'App\Locating\\ServiceInterface\\Locator',
        'expected_kind' => 'interface',
        'target_prefix' => 'src/ServiceInterface/Address/Location/Legacy',
        'target_namespace_prefix' => 'App\Locating\\ServiceInterface\\Address\\Location\\Legacy',
        'root_name_pattern' => '/Address|Suggest|Ranker|Normalizer/',
    ],
];

$rows = [];
$warnings = [];
$violations = [];
$summary = [
    'address_service_files' => 0,
    'address_service_interface_files' => 0,
    'root_address_candidates' => 0,
    'nested_address_candidates' => 0,
    'smartresponsor_namespace_files' => 0,
    'missing_or_unexpected_namespace_files' => 0,
    'rename_before_move_candidates' => 0,
    'external_use_references' => 0,
];

function lc15_extract_namespace(string $code): ?string
{
    if (preg_match('/^namespace\s+([^;]+);/m', $code, $m)) {
        return trim($m[1]);
    }

    return null;
}

function lc15_extract_kind(string $code, string $declaredName): ?string
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

function lc15_has_service_suffix(string $nameEntity, string $kind): bool
{
    if ($kind === 'interface') {
        return str_ends_with($nameEntity, 'Interface');
    }

    foreach (['Service', 'Capability', 'Factory', 'Guard', 'Policy', 'Router', 'Ranker', 'Normalizer', 'Provider', 'Reader', 'Writer', 'Resolver', 'Mapper', 'Manager', 'Decorator'] as $suffix) {
        if (str_ends_with($nameEntity, $suffix)) {
            return true;
        }
    }

    return false;
}

function lc15_suggested_name(string $nameEntity, string $kind): string
{
    if ($kind === 'interface') {
        if (str_ends_with($nameEntity, 'Interface')) {
            return $nameEntity;
        }

        return $nameEntity . 'Interface';
    }

    if (lc15_has_service_suffix($nameEntity, $kind)) {
        return $nameEntity;
    }

    if (str_contains($nameEntity, 'Suggest') || str_contains($nameEntity, 'Reverse')) {
        return $nameEntity . 'Capability';
    }

    return $nameEntity . 'Service';
}

foreach ($scopes as $scope => $config) {
    $directory = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $config['dir']);
    if (!is_dir($directory)) {
        $warnings[] = sprintf('LC-15 address legacy directory is absent: %s', $config['dir']);
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || $file->getExtension() !== 'php') {
            continue;
        }

        $absolutePath = $file->getPathname();
        $relativePath = str_replace('\\', '/', substr($absolutePath, strlen($root) + 1));
        $declaredName = pathinfo($absolutePath, PATHINFO_FILENAME);

        if (isset($config['root_name_pattern']) && $file->getPath() === $directory && preg_match($config['root_name_pattern'], $declaredName) !== 1) {
            continue;
        }

        if (isset($config['root_name_pattern']) && $file->getPath() !== $directory) {
            continue;
        }

        $code = (string) file_get_contents($absolutePath);
        $namespace = lc15_extract_namespace($code);
        $kind = lc15_extract_kind($code, $declaredName);
        $effectiveKind = $kind ?? $config['expected_kind'];
        $suggestedName = lc15_suggested_name($declaredName, $effectiveKind);
        $hasCanonicalSuffix = lc15_has_service_suffix($declaredName, $effectiveKind);
        $targetPath = $config['target_prefix'] . '/' . $suggestedName . '.php';
        $uses = [];

        if (preg_match_all('/^use\s+([^;]+);/m', $code, $matches)) {
            $uses = array_map('trim', $matches[1]);
        }

        if (str_contains($scope, 'interface')) {
            ++$summary['address_service_interface_files'];
        } else {
            ++$summary['address_service_files'];
        }

        if (isset($config['root_name_pattern'])) {
            ++$summary['root_address_candidates'];
        } else {
            ++$summary['nested_address_candidates'];
        }

        if ($namespace !== null && str_starts_with($namespace, 'App\Locating\\')) {
            ++$summary['smartresponsor_namespace_files'];
        } else {
            ++$summary['missing_or_unexpected_namespace_files'];
        }

        if (!$hasCanonicalSuffix || $suggestedName !== $declaredName) {
            ++$summary['rename_before_move_candidates'];
        }

        $externalRefs = array_values(array_filter($uses, static fn (string $use): bool => str_starts_with($use, 'App\Locating\\') && !str_contains($use, 'ServiceInterface\\Locator\\Address')));
        $summary['external_use_references'] += count($externalRefs);

        if ($kind !== $config['expected_kind']) {
            $warnings[] = sprintf('LC-15 unexpected kind in %s: expected %s, got %s', $relativePath, $config['expected_kind'], $kind ?? '[missing]');
        }

        if ($namespace === null || !str_starts_with($namespace, $config['namespace_prefix'])) {
            $warnings[] = sprintf('LC-15 namespace drift in %s: expected prefix %s, got %s', $relativePath, $config['namespace_prefix'], $namespace ?? '[missing]');
        }

        $rows[] = [
            'scope' => $scope,
            'path' => $relativePath,
            'declared_name' => $declaredName,
            'kind' => $kind,
            'namespace' => $namespace,
            'has_canonical_suffix' => $hasCanonicalSuffix,
            'suggested_name' => $suggestedName,
            'target_namespace_prefix' => $config['target_namespace_prefix'],
            'suggested_target_path' => $targetPath,
            'external_use_references' => implode('|', $externalRefs),
        ];
    }
}

usort($rows, static fn (array $a, array $b): int => [$a['scope'], $a['path']] <=> [$b['scope'], $b['path']]);

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-legacy-audit-latest.json',
    json_encode([
        'component' => 'Locating',
        'wave' => 'LC-15',
        'scope' => 'Address-focused Smartresponsor Locator legacy retirement readiness',
        'summary' => $summary,
        'violations' => $violations,
        'warnings' => $warnings,
        'rows' => $rows,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$csv = fopen($reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-legacy-audit-latest.csv', 'wb');
fputcsv($csv, ['scope', 'path', 'declared_name', 'kind', 'namespace', 'has_canonical_suffix', 'suggested_name', 'target_namespace_prefix', 'suggested_target_path', 'external_use_references']);
foreach ($rows as $row) {
    fputcsv($csv, [
        $row['scope'],
        $row['path'],
        $row['declared_name'],
        $row['kind'] ?? '',
        $row['namespace'] ?? '',
        $row['has_canonical_suffix'] ? 'yes' : 'no',
        $row['suggested_name'],
        $row['target_namespace_prefix'],
        $row['suggested_target_path'],
        $row['external_use_references'],
    ]);
}
fclose($csv);

$shortlist = [];
$shortlist[] = '# Locating LC-15 service locator address legacy shortlist';
$shortlist[] = '';
$shortlist[] = 'This generated shortlist narrows the large `App\Locating\\Service\\Locator` retirement track to address-focused classes and interfaces.';
$shortlist[] = '';
$shortlist[] = '| Scope | File | Current nameEntity | Suggested nameEntity | Suggested target path | Blocker notes |';
$shortlist[] = '| --- | --- | --- | --- | --- | --- |';
foreach ($rows as $row) {
    $blockers = [];
    if (!$row['has_canonical_suffix']) {
        $blockers[] = 'rename before physical move';
    }
    if ($row['external_use_references'] !== '') {
        $blockers[] = 'external Smartresponsor use refs';
    }
    if ($blockers === []) {
        $blockers[] = 'low-risk candidate';
    }

    $shortlist[] = sprintf(
        '| `%s` | `%s` | `%s` | `%s` | `%s` | %s |',
        $row['scope'],
        $row['path'],
        $row['declared_name'],
        $row['suggested_name'],
        $row['suggested_target_path'],
        implode('; ', $blockers)
    );
}
if ($rows === []) {
    $shortlist[] = '| - | - | - | - | - | No address legacy candidates found. |';
}
$shortlist[] = '';
file_put_contents($reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-legacy-shortlist.md', implode(PHP_EOL, $shortlist));

if ($violations !== []) {
    fwrite(STDERR, "Locating LC-15 service locator address legacy canon failed:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, ' - ' . $violation . "\n");
    }
    exit(1);
}

echo sprintf(
    "Locating LC-15 address legacy audit completed: %d service files, %d service-interface files, %d rename-before-move candidates.\n",
    $summary['address_service_files'],
    $summary['address_service_interface_files'],
    $summary['rename_before_move_candidates']
);

if ($warnings !== []) {
    echo sprintf("Warnings recorded: %d. See report/locating-service-locator-address-legacy-audit-latest.json.\n", count($warnings));
}
