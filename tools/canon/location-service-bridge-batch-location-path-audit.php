<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

$expectedMoves = [
    'src/Service/Bridge/Batch/LegacyAddressResultFactory.php' => 'src/Service/Bridge/Batch/Location/LegacyAddressResultFactory.php',
    'src/ServiceInterface/Bridge/Batch/LegacyAddressResultFactoryInterface.php' => 'src/ServiceInterface/Bridge/Batch/Location/LegacyAddressResultFactoryInterface.php',
];

$violations = [];
$warnings = [];
$rows = [];

foreach ($expectedMoves as $old => $new) {
    $oldPath = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old);
    $newPath = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $new);

    $oldExists = is_file($oldPath);
    $newExists = is_file($newPath);

    if ($oldExists) {
        $violations[] = 'Legacy bridge batch service path still exists: ' . $old;
    }

    if ($newExists) {
        $violations[] = 'Retired Legacy bridge batch location path still exists: ' . $new;
    }

    $namespace = null;
    $kind = null;
    $nameEntity = basename($new, '.php');
    if ($newExists) {
        $code = (string) file_get_contents($newPath);
        if (preg_match('/^namespace\s+([^;]+);/m', $code, $m)) {
            $namespace = $m[1];
        }
        if (preg_match('/^\s*(?:final\s+|abstract\s+)?class\s+' . preg_quote($nameEntity, '/') . '\b/m', $code)) {
            $kind = 'class';
        } elseif (preg_match('/^\s*interface\s+' . preg_quote($nameEntity, '/') . '\b/m', $code)) {
            $kind = 'interface';
        }
    }

    $expectedNamespace = str_starts_with($new, 'src/ServiceInterface/')
        ? 'App\Locating\\ServiceInterface\\Bridge\\Batch\\Location'
        : 'App\Locating\\Service\\Bridge\\Batch\\Location';

    if ($newExists && $namespace !== $expectedNamespace) {
        $violations[] = sprintf('Unexpected namespace for %s: expected %s, got %s', $new, $expectedNamespace, $namespace ?? '[missing]');
    }

    if ($newExists && str_starts_with($new, 'src/ServiceInterface/') && $kind !== 'interface') {
        $violations[] = sprintf('Retired ServiceInterface bridge batch file has an unexpected declaration: %s', $new);
    }

    if ($newExists && str_starts_with($new, 'src/Service/') && $kind !== 'class') {
        $violations[] = sprintf('Retired Service bridge batch file has an unexpected declaration: %s', $new);
    }

    $rows[] = [
        'old_path' => $old,
        'new_path' => $new,
        'old_exists' => $oldExists,
        'new_exists' => $newExists,
        'namespace' => $namespace,
        'kind' => $kind,
    ];
}

foreach ([
    'src/Service/Bridge' => $root . '/src/Service/Bridge',
    'src/ServiceInterface/Bridge' => $root . '/src/ServiceInterface/Bridge',
] as $relativeBridgeRoot => $absoluteBridgeRoot) {
    if (is_dir($absoluteBridgeRoot)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($absoluteBridgeRoot, FilesystemIterator::SKIP_DOTS));
        foreach ($files as $fileInfo) {
            if ($fileInfo instanceof SplFileInfo && $fileInfo->isFile()) {
                $violations[] = 'Retired Bridge service surface still exists under ' . $relativeBridgeRoot . ': ' . $fileInfo->getFilename();
            }
        }
    }
}

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-bridge-batch-location-path-audit-latest.json',
    json_encode([
        'component' => 'Locating',
        'wave' => 'LC-13',
        'scope' => 'Service/Bridge/Batch/Location path normalization',
        'violations' => $violations,
        'warnings' => $warnings,
        'rows' => $rows,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$csv = fopen($reportDir . DIRECTORY_SEPARATOR . 'locating-service-bridge-batch-location-path-audit-latest.csv', 'wb');
fputcsv($csv, ['old_path', 'new_path', 'old_exists', 'new_exists', 'namespace', 'kind'], ',', '"', '');
foreach ($rows as $row) {
    fputcsv($csv, [
        $row['old_path'],
        $row['new_path'],
        $row['old_exists'] ? 'yes' : 'no',
        $row['new_exists'] ? 'yes' : 'no',
        $row['namespace'] ?? '',
        $row['kind'] ?? '',
    ], ',', '"', '');
}
fclose($csv);

if ($violations !== []) {
    fwrite(STDERR, "Locating LC-13 bridge batch service path canon failed:
");
    foreach ($violations as $violation) {
        fwrite(STDERR, ' - ' . $violation . "
");
    }
    exit(1);
}

echo "Locating LC-13 bridge batch service path canon passed.
";
