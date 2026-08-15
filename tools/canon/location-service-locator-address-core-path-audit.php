<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$expectedFiles = [
    'src/Service/Address/Location/AddressParseService.php' => 'namespace App\Locating\\Service\\Address\\Location;',
    'src/Service/Address/Location/AddressStandardizeService.php' => 'namespace App\Locating\\Service\\Address\\Location;',
    'src/ServiceInterface/Address/Location/AddressParseServiceInterface.php' => 'namespace App\Locating\\ServiceInterface\\Address\\Location;',
    'src/ServiceInterface/Address/Location/AddressStandardizeServiceInterface.php' => 'namespace App\Locating\\ServiceInterface\\Address\\Location;',
];

$retiredFiles = [
    'src/Service/Locator/Address/AddressParseService.php',
    'src/Service/Locator/Address/AddressStandardizeService.php',
    'src/ServiceInterface/Locator/Address/AddressParseServiceInterface.php',
    'src/ServiceInterface/Locator/Address/AddressStandardizeServiceInterface.php',
];

foreach ($expectedFiles as $relativePath => $expectedNamespace) {
    $absolutePath = $root.'/'.$relativePath;
    if (!is_file($absolutePath)) {
        $errors[] = 'Missing canonical LC-17 file: '.$relativePath;
        continue;
    }

    $source = (string) file_get_contents($absolutePath);
    if (!str_contains($source, $expectedNamespace)) {
        $errors[] = 'Unexpected namespace in '.$relativePath.'; expected '.$expectedNamespace;
    }
}

foreach ($retiredFiles as $relativePath) {
    if (is_file($root.'/'.$relativePath)) {
        $errors[] = 'Retired LC-17 legacy path is still present: '.$relativePath;
    }
}

$kernelPath = $root.'/src/Infrastructure/Provider/Location/Http/Kernel.php';
if (is_file($kernelPath)) {
    $kernel = (string) file_get_contents($kernelPath);
    foreach ([
        'use App\Locating\\Service\\Provider\\Location\\Runtime\\Address\\AddressParseService;',
        'use App\Locating\\Service\\Provider\\Location\\Runtime\\Address\\AddressStandardizeService;',
    ] as $legacyUse) {
        if (str_contains($kernel, $legacyUse)) {
            $errors[] = 'Kernel still imports legacy LC-17 service: '.$legacyUse;
        }
    }
    foreach ([
        'use App\Locating\\Service\\Address\\Location\\AddressParseService;',
        'use App\Locating\\Service\\Address\\Location\\AddressStandardizeService;',
    ] as $canonicalUse) {
        if (!str_contains($kernel, $canonicalUse)) {
            $errors[] = 'Kernel is missing canonical LC-17 service import: '.$canonicalUse;
        }
    }
} else {
    $warnings[] = 'Kernel not found: src/Infrastructure/Provider/Location/Http/Kernel.php';
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

$report = [
    'wave' => 'LC-17',
    'nameEntity' => 'Service Locator Address core path normalization',
    'checked_at' => gmdate('c'),
    'expected_files' => array_keys($expectedFiles),
    'retired_files' => $retiredFiles,
    'warnings' => $warnings,
    'errors' => $errors,
];

file_put_contents(
    $reportDir.'/locating-service-locator-address-core-path-latest.json',
    json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
);

if ([] !== $warnings) {
    fwrite(STDERR, "LC-17 warnings:\n - ".implode("\n - ", $warnings)."\n");
}

if ([] !== $errors) {
    fwrite(STDERR, "LC-17 failed:\n - ".implode("\n - ", $errors)."\n");
    exit(1);
}

echo "LC-17 service locator address core path canon passed.\n";
