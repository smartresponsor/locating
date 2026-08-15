<?php

declare(strict_types=1);

/**
 * Locating LC-05 entity-family map.
 *
 * This report-only mapper prepares the next safe touched-file Entity wave.
 * It never renames, moves, deletes, or rewrites runtime classes.
 */

$root = dirname(__DIR__, 2);
$src = $root . DIRECTORY_SEPARATOR . 'src';
$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
$entityDir = $src . DIRECTORY_SEPARATOR . 'Entity';
$entityInterfaceDir = $src . DIRECTORY_SEPARATOR . 'EntityInterface';

if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    fwrite(STDERR, "Cannot create report directory: {$reportDir}\n");
    exit(2);
}

$entities = scanLayer($root, $entityDir, 'Entity');
$interfaces = scanLayer($root, $entityInterfaceDir, 'EntityInterface');
$warnings = [];
$families = [];

foreach ($entities as $entity) {
    $key = logicalFamilyName($entity['class'] ?? $entity['basename'], false);
    $families[$key]['family'] = $key;
    $families[$key]['entities'][] = $entity;
}
foreach ($interfaces as $interface) {
    $key = logicalFamilyName($interface['class'] ?? $interface['basename'], true);
    $families[$key]['family'] = $key;
    $families[$key]['interfaces'][] = $interface;
}
ksort($families);

foreach ($families as $family => &$record) {
    $record['entities'] = $record['entities'] ?? [];
    $record['interfaces'] = $record['interfaces'] ?? [];
    $record['entity_count'] = count($record['entities']);
    $record['interface_count'] = count($record['interfaces']);
    $record['status'] = classifyFamily($record);
    $record['recommended_next_action'] = recommendAction($record);

    if ($record['entity_count'] === 0) {
        $warnings[] = warning('missing_entity', $family, paths($record['interfaces']), 'EntityInterface family has no matching Entity class.');
    }
    if ($record['interface_count'] === 0) {
        $warnings[] = warning('missing_interface', $family, paths($record['entities']), 'Entity family has no matching EntityInterface contract.');
    }
    if ($record['entity_count'] > 1) {
        $warnings[] = warning('duplicate_entity_family', $family, paths($record['entities']), 'Multiple Entity classes map to the same logical family; choose canonical root vs nested bucket before moving.');
    }
    if ($record['interface_count'] > 1) {
        $warnings[] = warning('duplicate_interface_family', $family, paths($record['interfaces']), 'Multiple EntityInterface contracts map to the same logical family.');
    }

    foreach ($record['entities'] as $entity) {
        if (($entity['namespace'] ?? '') !== '' && !str_starts_with((string) $entity['namespace'], 'App\Locating\\')) {
            $warnings[] = warning('entity_namespace_drift', $family, [$entity['path']], 'Entity namespace is outside App\Locating\\ transition target.');
        }
        if (($entity['table'] ?? null) !== null && !str_starts_with((string) $entity['table'], 'location_')) {
            $warnings[] = warning('table_prefix_drift', $family, [$entity['path']], 'Doctrine table does not use required location_ prefix.');
        }
    }
    foreach ($record['interfaces'] as $interface) {
        if (!str_ends_with((string) $interface['basename'], 'Interface')) {
            $warnings[] = warning('interface_suffix_drift', $family, [$interface['path']], 'EntityInterface file/class must end with Interface.');
        }
        if (($interface['namespace'] ?? '') !== '' && !str_starts_with((string) $interface['namespace'], 'App\Locating\\')) {
            $warnings[] = warning('interface_namespace_drift', $family, [$interface['path']], 'EntityInterface namespace is outside App\Locating\\ transition target.');
        }
    }
}
unset($record);

$summary = [
    'generated_at' => gmdate('c'),
    'component' => 'Locating',
    'wave' => 'LC-05 entity-family map',
    'policy' => 'report-only touched-file preparation; no runtime class move, delete, or rewrite is performed',
    'entity_total' => count($entities),
    'entity_interface_total' => count($interfaces),
    'family_total' => count($families),
    'warning_total' => count($warnings),
    'warnings' => $warnings,
    'families' => array_values($families),
];

$jsonPath = $reportDir . DIRECTORY_SEPARATOR . 'locating-entity-family-map-latest.json';
$csvPath = $reportDir . DIRECTORY_SEPARATOR . 'locating-entity-family-map-latest.csv';
$shortlistPath = $reportDir . DIRECTORY_SEPARATOR . 'locating-entity-family-normalization-shortlist.md';

file_put_contents($jsonPath, json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);
writeFamilyCsv($csvPath, array_values($families));
writeShortlist($shortlistPath, array_values($families), $warnings);

printf("Locating LC-05 entity-family map complete. Entities: %d; interfaces: %d; families: %d; warnings: %d\n", count($entities), count($interfaces), count($families), count($warnings));
printf("Reports: %s, %s, %s\n", relativePath($root, $jsonPath), relativePath($root, $csvPath), relativePath($root, $shortlistPath));

if ($warnings !== []) {
    echo "Top LC-05 warnings:\n";
    foreach (array_slice($warnings, 0, 20) as $warning) {
        printf("- [%s] %s: %s\n", $warning['code'], implode('; ', $warning['paths']), $warning['message']);
    }
    echo "LC-05 is report-only and exits successfully so LC-06 can apply a small Entity touched-files rename/move set.\n";
}

exit(0);

function scanLayer(string $root, string $dir, string $layer): array
{
    if (!is_dir($dir)) {
        return [];
    }

    $records = [];
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $fileInfo) {
        if (!$fileInfo instanceof SplFileInfo || $fileInfo->getExtension() !== 'php') {
            continue;
        }
        $path = $fileInfo->getPathname();
        $text = (string) file_get_contents($path);
        $relativePath = relativePath($root, $path);
        $records[] = [
            'layer' => $layer,
            'path' => $relativePath,
            'basename' => $fileInfo->getBasename('.php'),
            'namespace' => detectNamespace($text),
            'class' => detectClassName($text),
            'kind' => detectKind($text),
            'table' => detectDoctrineTableName($text),
            'implements' => detectImplements($text),
            'under_locator_bucket' => str_contains(str_replace('\\', '/', $relativePath), '/Locator/'),
        ];
    }
    usort($records, static fn(array $a, array $b): int => strcmp($a['path'], $b['path']));

    return $records;
}

function logicalFamilyName(?string $nameEntity, bool $isInterface): string
{
    $nameEntity = $nameEntity === null || $nameEntity === '' ? 'Unknown' : $nameEntity;
    return $isInterface ? (preg_replace('/Interface$/', '', $nameEntity) ?: $nameEntity) : $nameEntity;
}

function classifyFamily(array $record): string
{
    if ($record['entity_count'] === 1 && $record['interface_count'] === 1) {
        return 'paired';
    }
    if ($record['entity_count'] > 1 || $record['interface_count'] > 1) {
        return 'ambiguous_duplicate_family';
    }
    if ($record['entity_count'] === 0) {
        return 'interface_without_entity';
    }
    return 'entity_without_interface';
}

function recommendAction(array $record): string
{
    return match ($record['status']) {
        'paired' => 'Keep as stable family; later verify entity implements contract and table prefix.',
        'ambiguous_duplicate_family' => 'Do not auto-move. Pick canonical owner path and retire duplicates with explicit touched-file wave.',
        'interface_without_entity' => 'Either create matching Entity or retire unused contract after usage scan.',
        'entity_without_interface' => 'Create matching EntityInterface only if the class is part of public component contract; otherwise document as private entity.',
        default => 'Manual review required.',
    };
}

function warning(string $code, string $family, array $paths, string $message): array
{
    return ['code' => $code, 'family' => $family, 'paths' => $paths, 'message' => $message];
}

function paths(array $records): array
{
    return array_values(array_map(static fn(array $record): string => (string) $record['path'], $records));
}

function detectNamespace(string $text): ?string
{
    return preg_match('/^namespace\s+([^;]+);/m', $text, $m) === 1 ? trim($m[1]) : null;
}

function detectClassName(string $text): ?string
{
    return preg_match('/\b(?:final\s+|abstract\s+)?(?:class|interface|enum|trait)\s+([A-Za-z_][A-Za-z0-9_]*)\b/', $text, $m) === 1 ? $m[1] : null;
}

function detectKind(string $text): ?string
{
    return preg_match('/\b(class|interface|enum|trait)\s+[A-Za-z_][A-Za-z0-9_]*\b/', $text, $m) === 1 ? $m[1] : null;
}

function detectDoctrineTableName(string $text): ?string
{
    if (preg_match('/#\[ORM\\Table\(name:\s*[\'\"]([^\'\"]+)[\'\"]/', $text, $m) === 1) {
        return $m[1];
    }
    if (preg_match('/#\[ORM\\Entity\([^\]]*table:\s*[\'\"]([^\'\"]+)[\'\"]/', $text, $m) === 1) {
        return $m[1];
    }
    return null;
}

function detectImplements(string $text): array
{
    if (preg_match('/\bclass\s+[A-Za-z_][A-Za-z0-9_]*[^\{]*\bimplements\s+([^\{]+)/s', $text, $m) !== 1) {
        return [];
    }
    return array_values(array_filter(array_map(static fn(string $part): string => trim($part, " \t\n\r\\"), explode(',', $m[1]))));
}

function writeFamilyCsv(string $path, array $families): void
{
    $handle = fopen($path, 'wb');
    if ($handle === false) {
        throw new RuntimeException("Cannot write CSV report: {$path}");
    }
    fputcsv($handle, ['family', 'status', 'entity_count', 'interface_count', 'entity_paths', 'interface_paths', 'recommended_next_action'], ',', '"', '');
    foreach ($families as $family) {
        fputcsv($handle, [
            $family['family'], $family['status'], (string) $family['entity_count'], (string) $family['interface_count'],
            implode('; ', paths($family['entities'])), implode('; ', paths($family['interfaces'])), $family['recommended_next_action'],
        ], ',', '"', '');
    }
    fclose($handle);
}

function writeShortlist(string $path, array $families, array $warnings): void
{
    $lines = ['# Locating LC-05 Entity-family normalization shortlist', '', 'Generated: ' . gmdate('c'), '', 'This report is generated by `composer canon:entity-family`. It is a planning artifact for the next touched-files wave; it is not a destructive migration script.', '', '## Priority families', ''];
    $interesting = array_values(array_filter($families, static fn(array $family): bool => $family['status'] !== 'paired'));
    if ($interesting === []) {
        $lines[] = '- No unpaired or duplicate Entity families detected.';
    } else {
        foreach (array_slice($interesting, 0, 60) as $family) {
            $lines[] = sprintf('- `%s` — `%s`; %s', $family['family'], $family['status'], $family['recommended_next_action']);
            foreach (array_merge(paths($family['entities']), paths($family['interfaces'])) as $pathItem) {
                $lines[] = sprintf('  - `%s`', $pathItem);
            }
        }
    }
    $lines[] = '';
    $lines[] = '## Warning summary';
    $lines[] = '';
    if ($warnings === []) {
        $lines[] = '- No warnings.';
    } else {
        foreach (array_slice($warnings, 0, 80) as $warning) {
            $lines[] = sprintf('- `%s` / `%s`: %s', $warning['code'], $warning['family'], $warning['message']);
        }
    }
    file_put_contents($path, implode(PHP_EOL, $lines) . PHP_EOL);
}

function relativePath(string $root, string $path): string
{
    $root = rtrim(str_replace('\\', '/', $root), '/');
    $path = str_replace('\\', '/', $path);
    return ltrim(str_starts_with($path, $root) ? substr($path, strlen($root)) : $path, '/');
}

