<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

$expected = [
    'src/Service/Address/Location/AddressSuggestRanker.php' => [
        'namespace App\Locating\\Service\\Address\\Location;',
        'final class AddressSuggestRanker implements AddressSuggestRankerInterface',
        'use App\Locating\\ServiceInterface\\Address\\Location\\AddressSuggestRankerInterface;',
    ],
    'src/ServiceInterface/Address/Location/AddressSuggestRankerInterface.php' => [
        'namespace App\Locating\\ServiceInterface\\Address\\Location;',
        'interface AddressSuggestRankerInterface',
    ],
];

$retired = [
    'src/Service/Locator/AddressSuggestRanker.php',
    'src/ServiceInterface/Locator/AddressSuggestRankerInterface.php',
];

$errors = [];
$warnings = [];

foreach ($expected as $relativePath => $needles) {
    $absolutePath = $root . '/' . $relativePath;
    if (!is_file($absolutePath)) {
        $errors[] = "Missing LC-18 canonical file: {$relativePath}";
        continue;
    }

    $contents = (string) file_get_contents($absolutePath);
    foreach ($needles as $needle) {
        if (!str_contains($contents, $needle)) {
            $errors[] = "Canonical file {$relativePath} does not contain expected marker: {$needle}";
        }
    }

    $lintCommand = 'php -l ' . escapeshellarg($absolutePath) . ' 2>&1';
    exec($lintCommand, $lintOutput, $lintCode);
    if (0 !== $lintCode) {
        $errors[] = "PHP lint failed for {$relativePath}: " . implode(' ', $lintOutput);
    }
}

foreach ($retired as $relativePath) {
    if (is_file($root . '/' . $relativePath)) {
        $errors[] = "Retired LC-18 legacy path is still present: {$relativePath}";
    }
}

$srcRoot = $root . '/src';
$referenceNeedles = [
    'App\Locating\\ServiceInterface\\Provider\\Location\\Runtime\\AddressSuggestRankerInterface',
    'App\Locating\\Service\\Provider\\Location\\Runtime\\AddressSuggestRanker',
];

if (is_dir($srcRoot)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($srcRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || !$file->isFile() || 'php' !== $file->getExtension()) {
            continue;
        }

        $path = $file->getPathname();
        $relative = str_replace('\\', '/', substr($path, strlen($root) + 1));
        if (in_array($relative, $retired, true)) {
            continue;
        }

        $contents = (string) file_get_contents($path);
        foreach ($referenceNeedles as $needle) {
            if (str_contains($contents, $needle)) {
                $errors[] = "LC-18 retired AddressSuggestRanker FQCN reference remains in {$relative}: {$needle}";
            }
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

$report = [
    'wave' => 'LC-18',
    'nameEntity' => 'Service Locator Address ranker path normalization',
    'expected' => array_keys($expected),
    'retired' => $retired,
    'errors' => $errors,
    'warnings' => $warnings,
];

file_put_contents(
    $reportDir . '/locating-service-locator-address-ranker-path-latest.json',
    json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

if ([] !== $errors) {
    fwrite(STDERR, "LC-18 Service Locator Address ranker path canon failed:" . PHP_EOL);
    foreach ($errors as $error) {
        fwrite(STDERR, ' - ' . $error . PHP_EOL);
    }
    exit(1);
}

foreach ($warnings as $warning) {
    fwrite(STDERR, 'Warning: ' . $warning . PHP_EOL);
}

fwrite(STDOUT, "LC-18 Service Locator Address ranker path canon passed." . PHP_EOL);
