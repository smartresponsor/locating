<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

$root = dirname(__DIR__, 2);
$src = $root . '/src';

$violations = [];
$movedCandidates = [];

$scan = static function (string $directory): array {
    if (!is_dir($directory)) {
        return [];
    }

    $files = [];
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || !$file->isFile()) {
            continue;
        }

        if ('php' !== strtolower($file->getExtension())) {
            continue;
        }

        $files[] = $file->getPathname();
    }

    sort($files);

    return $files;
};

$readNamespace = static function (string $path): ?string {
    $contents = file_get_contents($path);
    if (false === $contents) {
        return null;
    }

    if (1 === preg_match('/^namespace\s+([^;]+);/m', $contents, $matches)) {
        return trim($matches[1]);
    }

    return null;
};

foreach (glob($src . '/Entity/*.php') ?: [] as $path) {
    $namespace = $readNamespace($path);
    if ('App\Locating\\Entity\\Location' === $namespace) {
        $violations[] = [
            'type' => 'entity_path_namespace_mismatch',
            'path' => substr($path, strlen($root) + 1),
            'namespace' => $namespace,
            'expected_path_prefix' => 'src/Model/Location/',
        ];
    }
}

foreach (glob($src . '/EntityInterface/*.php') ?: [] as $path) {
    $namespace = $readNamespace($path);
    if ('App\Locating\\EntityInterface\\Location' === $namespace) {
        $violations[] = [
            'type' => 'entity_interface_path_namespace_mismatch',
            'path' => substr($path, strlen($root) + 1),
            'namespace' => $namespace,
            'expected_path_prefix' => 'src/ModelInterface/Location/',
        ];
    }
}

foreach ($scan($src . '/Entity/Location') as $path) {
    $namespace = $readNamespace($path);
    if ('App\Locating\\Entity\\Location' !== $namespace) {
        $violations[] = [
            'type' => 'entity_location_namespace_drift',
            'path' => substr($path, strlen($root) + 1),
            'namespace' => $namespace,
            'expected_namespace' => 'App\Locating\\Entity\\Location',
        ];
    }
}

foreach ($scan($src . '/EntityInterface/Location') as $path) {
    $namespace = $readNamespace($path);
    if ('App\Locating\\EntityInterface\\Location' !== $namespace) {
        $violations[] = [
            'type' => 'entity_interface_location_namespace_drift',
            'path' => substr($path, strlen($root) + 1),
            'namespace' => $namespace,
            'expected_namespace' => 'App\Locating\\EntityInterface\\Location',
        ];
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    fwrite(STDERR, "Unable to create report directory: {$reportDir}\n");
    exit(2);
}

$payload = [
    'component' => 'Locating',
    'stage' => 'LC-06 entity location path normalization',
    'generated_at' => (new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM),
    'violation_count' => count($violations),
    'violations' => $violations,
];

file_put_contents(
    $reportDir . '/locating-entity-location-path-latest.json',
    json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n"
);

$csv = fopen($reportDir . '/locating-entity-location-path-latest.csv', 'wb');
if (false !== $csv) {
    fputcsv($csv, ['type', 'path', 'namespace', 'expected'], ',', '"', '');
    foreach ($violations as $violation) {
        fputcsv($csv, [
            $violation['type'] ?? '',
            $violation['path'] ?? '',
            $violation['namespace'] ?? '',
            $violation['expected_namespace'] ?? ($violation['expected_path_prefix'] ?? ''),
        ], ',', '"', '');
    }
    fclose($csv);
}

if ([] !== $violations) {
    fwrite(STDERR, sprintf("Locating LC-06 entity path canon found %d violation(s).\n", count($violations)));
    exit(1);
}

fwrite(STDOUT, "Locating LC-06 entity path canon passed.\n");
