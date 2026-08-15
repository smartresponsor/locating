<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacyPaths = [
    'src/Service/Locator/Provider/Ranker.php',
    'src/ServiceInterface/Locator/RankerInterface.php',
];
$canonicalPaths = [
    'src/Service/Provider/Location/ProviderRankerService.php' => [
        'namespace App\Locating\\Service\\Provider\\Location;',
        'final class ProviderRankerService implements ProviderRankerServiceInterface',
        'public static function sort(array $items): array',
    ],
    'src/ServiceInterface/Provider/Location/ProviderRankerServiceInterface.php' => [
        'namespace App\Locating\\ServiceInterface\\Provider\\Location;',
        'interface ProviderRankerServiceInterface',
        'public static function sort(array $items): array;',
    ],
    'src/Service/Locator/Provider/ProviderRouter.php' => [
        'use App\Locating\\Service\\Provider\\Location\\ProviderRankerService;',
        'ProviderRankerService::sort($res)',
    ],
];

$errors = [];
foreach ($legacyPaths as $relativePath) {
    if (is_file($root . DIRECTORY_SEPARATOR . $relativePath)) {
        $errors[] = sprintf('Legacy provider ranker path still exists: %s', $relativePath);
    }
}

foreach ($canonicalPaths as $relativePath => $needles) {
    $absolutePath = $root . DIRECTORY_SEPARATOR . $relativePath;
    if (!is_file($absolutePath)) {
        $errors[] = sprintf('Missing canonical provider ranker path: %s', $relativePath);
        continue;
    }

    $contents = (string) file_get_contents($absolutePath);
    foreach ($needles as $needle) {
        if (!str_contains($contents, $needle)) {
            $errors[] = sprintf('Canonical provider ranker file %s is missing expected marker: %s', $relativePath, $needle);
        }
    }
}

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-27',
    'scope' => 'Service Locator Provider ranker legacy path normalization',
    'legacy_paths' => $legacyPaths,
    'canonical_paths' => array_keys($canonicalPaths),
    'errors' => $errors,
];
file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-provider-ranker-legacy-path-latest.json',
    json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[location-service-locator-provider-ranker-legacy-path] ERROR: ' . $error . PHP_EOL);
    }
    exit(1);
}

fwrite(STDOUT, '[location-service-locator-provider-ranker-legacy-path] OK' . PHP_EOL);
