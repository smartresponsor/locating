<?php

declare(strict_types=1);

/**
 * Locating LC-04 entity-first inventory gate.
 *
 * This scanner is intentionally non-destructive. It reads src/Entity and
 * src/EntityInterface, records class/interface shape, detects duplicated
 * logical names, and highlights table-prefix / namespace drift candidates.
 */

$root = dirname(__DIR__, 2);
$src = $root . DIRECTORY_SEPARATOR . 'src';
$entityDir = $src . DIRECTORY_SEPARATOR . 'Entity';
$entityInterfaceDir = $src . DIRECTORY_SEPARATOR . 'EntityInterface';
$reportDir = $root . DIRECTORY_SEPARATOR . 'report';

if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    fwrite(STDERR, "Cannot create report directory: {$reportDir}\n");
    exit(2);
}

$records = [];
$warnings = [];

foreach ([
    'Entity' => $entityDir,
    'EntityInterface' => $entityInterfaceDir,
] as $section => $dir) {
    if (!is_dir($dir)) {
        $warnings[] = ['severity' => 'error', 'section' => $section, 'path' => relativePath($root, $dir), 'message' => "Missing {$section} directory."];
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));

    foreach ($iterator as $fileInfo) {
        if (!$fileInfo instanceof SplFileInfo || $fileInfo->getExtension() !== 'php') {
            continue;
        }

        $path = $fileInfo->getPathname();
        $text = (string) file_get_contents($path);
        $relativePath = relativePath($root, $path);
        $class = detectClassName($text);
        $namespace = detectNamespace($text);
        $kind = detectKind($text);
        $table = detectDoctrineTableName($text);
        $basename = $fileInfo->getBasename('.php');
        $expectedSuffix = $section === 'EntityInterface' ? 'Interface' : '';
        $hasExpectedSuffix = $expectedSuffix === '' || str_ends_with($basename, $expectedSuffix);
        $underLocatorBucket = str_contains(str_replace('\\', '/', $relativePath), '/Locator/');
        $logicalName = $section === 'EntityInterface' ? preg_replace('/Interface$/', '', $basename) : $basename;

        $records[] = [
            'section' => $section,
            'path' => $relativePath,
            'namespace' => $namespace,
            'kind' => $kind,
            'class' => $class,
            'basename' => $basename,
            'logical_name' => $logicalName,
            'under_locator_bucket' => $underLocatorBucket,
            'has_expected_suffix' => $hasExpectedSuffix,
            'doctrine_table' => $table,
            'table_prefix_status' => classifyTablePrefix($table),
        ];

        if ($class === null) {
            $warnings[] = ['severity' => 'warning', 'section' => $section, 'path' => $relativePath, 'message' => 'PHP file has no detectable class/interface declaration.'];
        }
        if ($kind !== ($section === 'EntityInterface' ? 'interface' : 'class')) {
            $warnings[] = ['severity' => 'warning', 'section' => $section, 'path' => $relativePath, 'message' => "Unexpected declaration kind '{$kind}' for {$section} layer."];
        }
        if (!$hasExpectedSuffix) {
            $warnings[] = ['severity' => 'warning', 'section' => $section, 'path' => $relativePath, 'message' => 'EntityInterface file must end with Interface suffix.'];
        }
        if ($table !== null && !str_starts_with($table, 'location_')) {
            $warnings[] = ['severity' => 'warning', 'section' => $section, 'path' => $relativePath, 'message' => "Doctrine table '{$table}' does not start with required location_ prefix."];
        }
    }
}

$byLogical = [];
foreach ($records as $record) {
    $key = $record['section'] . ':' . $record['logical_name'];
    $byLogical[$key][] = $record['path'];
}
foreach ($byLogical as $key => $paths) {
    if (count($paths) > 1) {
        [$section, $logicalName] = explode(':', $key, 2);
        $warnings[] = ['severity' => 'warning', 'section' => $section, 'path' => implode('; ', $paths), 'message' => "Duplicate logical {$section} nameEntity '{$logicalName}' across multiple paths."];
    }
}

$summary = [
    'generated_at' => gmdate('c'),
    'component' => 'Locating',
    'rule' => 'LC-04 entity-first inventory only; no runtime file moves are performed by this gate.',
    'entity_count' => count(array_filter($records, static fn(array $r): bool => $r['section'] === 'Entity')),
    'entity_interface_count' => count(array_filter($records, static fn(array $r): bool => $r['section'] === 'EntityInterface')),
    'warning_count' => count($warnings),
    'warnings' => $warnings,
    'records' => $records,
];

$jsonPath = $reportDir . DIRECTORY_SEPARATOR . 'locating-entity-first-inventory-latest.json';
$csvPath = $reportDir . DIRECTORY_SEPARATOR . 'locating-entity-first-inventory-latest.csv';
file_put_contents($jsonPath, json_encode($summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);
writeCsv($csvPath, $records);

printf("Locating LC-04 entity-first inventory complete. Entities: %d; interfaces: %d; warnings: %d\n", $summary['entity_count'], $summary['entity_interface_count'], $summary['warning_count']);
printf("Reports: %s and %s\n", relativePath($root, $jsonPath), relativePath($root, $csvPath));

if ($warnings !== []) {
    echo "Top warnings:\n";
    foreach (array_slice($warnings, 0, 20) as $warning) {
        printf("- [%s] %s: %s\n", $warning['severity'], $warning['path'], $warning['message']);
    }
    echo "LC-04 is report-only and exits successfully so the next wave can use the inventory.\n";
}

exit(0);

function detectNamespace(string $text): ?string
{
    if (preg_match('/^namespace\s+([^;]+);/m', $text, $m) === 1) {
        return trim($m[1]);
    }

    return null;
}

function detectClassName(string $text): ?string
{
    if (preg_match('/\b(?:final\s+|abstract\s+)?(?:class|interface|enum|trait)\s+([A-Za-z_][A-Za-z0-9_]*)\b/', $text, $m) === 1) {
        return $m[1];
    }

    return null;
}

function detectKind(string $text): ?string
{
    if (preg_match('/\b(class|interface|enum|trait)\s+[A-Za-z_][A-Za-z0-9_]*\b/', $text, $m) === 1) {
        return $m[1];
    }

    return null;
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

function classifyTablePrefix(?string $table): string
{
    if ($table === null) {
        return 'not_declared';
    }

    return str_starts_with($table, 'location_') ? 'canonical_location_prefix' : 'non_canonical_prefix';
}

function writeCsv(string $path, array $records): void
{
    $handle = fopen($path, 'wb');
    if ($handle === false) {
        throw new RuntimeException("Cannot write CSV report: {$path}");
    }

    $headers = ['section', 'path', 'namespace', 'kind', 'class', 'basename', 'logical_name', 'under_locator_bucket', 'has_expected_suffix', 'doctrine_table', 'table_prefix_status'];
    fputcsv($handle, $headers, ',', '"', '');
    foreach ($records as $record) {
        fputcsv($handle, array_map(static fn(string $key): string => scalarToString($record[$key] ?? ''), $headers), ',', '"', '');
    }
    fclose($handle);
}

function scalarToString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }
    if ($value === null) {
        return '';
    }

    return (string) $value;
}

function relativePath(string $root, string $path): string
{
    $root = rtrim(str_replace('\\', '/', $root), '/');
    $path = str_replace('\\', '/', $path);

    return ltrim(str_starts_with($path, $root) ? substr($path, strlen($root)) : $path, '/');
}

