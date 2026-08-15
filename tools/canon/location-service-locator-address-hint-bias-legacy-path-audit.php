<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacyPaths = [
    'src/Service/Locator/HintBias.php',
    'src/ServiceInterface/Locator/HintBiasInterface.php',
];
$canonicalPaths = [
    'src/Service/Address/Location/AddressHintBiasService.php' => [
        'namespace App\Locating\\Service\\Address\\Location;',
        'final class AddressHintBiasService implements AddressHintBiasServiceInterface',
    ],
    'src/ServiceInterface/Address/Location/AddressHintBiasServiceInterface.php' => [
        'namespace App\Locating\\ServiceInterface\\Address\\Location;',
        'interface AddressHintBiasServiceInterface',
    ],
];

$errors = [];
foreach ($legacyPaths as $relativePath) {
    if (is_file($root . DIRECTORY_SEPARATOR . $relativePath)) {
        $errors[] = sprintf('Legacy Locator hint-bias path still exists: %s', $relativePath);
    }
}

foreach ($canonicalPaths as $relativePath => $needles) {
    $absolutePath = $root . DIRECTORY_SEPARATOR . $relativePath;
    if (!is_file($absolutePath)) {
        $errors[] = sprintf('Missing canonical hint-bias path: %s', $relativePath);
        continue;
    }

    $contents = (string) file_get_contents($absolutePath);
    foreach ($needles as $needle) {
        if (!str_contains($contents, $needle)) {
            $errors[] = sprintf('Canonical hint-bias file %s is missing expected marker: %s', $relativePath, $needle);
        }
    }

    if (str_contains($contents, 'namespace App\Locating\\')) {
        $errors[] = sprintf('Canonical hint-bias file still uses Smartresponsor namespace: %s', $relativePath);
    }
}

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-26',
    'scope' => 'Service Locator Address hint-bias legacy path normalization',
    'legacy_paths' => $legacyPaths,
    'canonical_paths' => array_keys($canonicalPaths),
    'errors' => $errors,
];
file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-address-hint-bias-legacy-path-latest.json',
    json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[location-service-locator-address-hint-bias-legacy-path] ERROR: ' . $error . PHP_EOL);
    }
    exit(1);
}

fwrite(STDOUT, '[location-service-locator-address-hint-bias-legacy-path] OK' . PHP_EOL);
