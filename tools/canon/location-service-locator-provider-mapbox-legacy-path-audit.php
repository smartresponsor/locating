<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacyPath = $root.'/src/Service/Locator/Provider/MapboxProvider.php';
$canonicalPath = $root.'/src/Service/Provider/Location/MapboxProviderService.php';
$routerPath = $root.'/src/Service/Locator/Provider/ProviderRouter.php';

$errors = [];

if (is_file($legacyPath)) {
    $errors[] = 'Legacy Mapbox provider path still exists: src/Service/Locator/Provider/MapboxProvider.php';
}

if (!is_file($canonicalPath)) {
    $errors[] = 'Canonical Mapbox provider path is missing: src/Service/Provider/Location/MapboxProviderService.php';
} else {
    $code = file_get_contents($canonicalPath);
    if (!str_contains($code, 'namespace App\Locating\\Service\\Provider\\Location;')) {
        $errors[] = 'Canonical Mapbox provider must use App\Locating\\Service\\Provider\\Location namespace.';
    }
    if (!preg_match('/class\\s+MapboxProviderService\\b/', $code)) {
        $errors[] = 'Canonical Mapbox provider class must be MapboxProviderService.';
    }
    if (str_contains($code, 'namespace App\Locating\\Service\\Provider\\Location\\Runtime\\Provider;')) {
        $errors[] = 'Canonical Mapbox provider still contains legacy Smartresponsor provider namespace.';
    }
}

if (!is_file($routerPath)) {
    $errors[] = 'ProviderRouter not found for Mapbox provider reference check.';
} else {
    $router = file_get_contents($routerPath);
    if (!str_contains($router, 'use App\Locating\\Service\\Provider\\Location\\MapboxProviderService;')) {
        $errors[] = 'ProviderRouter must import MapboxProviderService.';
    }
    if (str_contains($router, 'new MapboxProvider(')) {
        $errors[] = 'ProviderRouter still instantiates legacy MapboxProvider.';
    }
    if (!str_contains($router, 'new MapboxProviderService(')) {
        $errors[] = 'ProviderRouter must instantiate MapboxProviderService.';
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir.'/locating-lc29-service-locator-provider-mapbox-legacy-path-latest.json',
    json_encode([
        'stage' => 'LC-29 service locator provider mapbox legacy path normalization',
        'legacy_path' => 'src/Service/Locator/Provider/MapboxProvider.php',
        'canonical_path' => 'src/Service/Provider/Location/MapboxProviderService.php',
        'router_path' => 'src/Service/Locator/Provider/ProviderRouter.php',
        'errors' => $errors,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
);

if ($errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[locating-lc29] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[locating-lc29] Mapbox provider legacy path normalization gate passed.'.PHP_EOL;
