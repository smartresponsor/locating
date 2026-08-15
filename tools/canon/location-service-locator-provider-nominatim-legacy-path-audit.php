<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacyPath = $root.'/src/Service/Locator/Provider/NominatimProvider.php';
$canonicalPath = $root.'/src/Service/Provider/Location/NominatimProviderService.php';
$routerPath = $root.'/src/Service/Locator/Provider/ProviderRouter.php';

$errors = [];

if (is_file($legacyPath)) {
    $errors[] = 'Legacy Nominatim provider path still exists: src/Service/Locator/Provider/NominatimProvider.php';
}

if (!is_file($canonicalPath)) {
    $errors[] = 'Canonical Nominatim provider path is missing: src/Service/Provider/Location/NominatimProviderService.php';
} else {
    $code = file_get_contents($canonicalPath);
    if (!str_contains($code, 'namespace App\Locating\\Service\\Provider\\Location;')) {
        $errors[] = 'Canonical Nominatim provider must use App\Locating\\Service\\Provider\\Location namespace.';
    }
    if (!preg_match('/class\\s+NominatimProviderService\\b/', $code)) {
        $errors[] = 'Canonical Nominatim provider class must be NominatimProviderService.';
    }
    if (str_contains($code, 'namespace App\Locating\\Service\\Provider\\Location\\Runtime\\Provider;')) {
        $errors[] = 'Canonical Nominatim provider still contains legacy Smartresponsor provider namespace.';
    }
}

if (!is_file($routerPath)) {
    $errors[] = 'ProviderRouter not found for Nominatim provider reference check.';
} else {
    $router = file_get_contents($routerPath);
    if (!str_contains($router, 'use App\Locating\\Service\\Provider\\Location\\NominatimProviderService;')) {
        $errors[] = 'ProviderRouter must import NominatimProviderService.';
    }
    if (str_contains($router, 'new NominatimProvider(')) {
        $errors[] = 'ProviderRouter still instantiates legacy NominatimProvider.';
    }
    if (!str_contains($router, 'new NominatimProviderService(')) {
        $errors[] = 'ProviderRouter must instantiate NominatimProviderService.';
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir.'/locating-lc30-service-locator-provider-nominatim-legacy-path-latest.json',
    json_encode([
        'stage' => 'LC-30 service locator provider nominatim legacy path normalization',
        'legacy_path' => 'src/Service/Locator/Provider/NominatimProvider.php',
        'canonical_path' => 'src/Service/Provider/Location/NominatimProviderService.php',
        'router_path' => 'src/Service/Locator/Provider/ProviderRouter.php',
        'errors' => $errors,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
);

if ($errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[locating-lc30] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[locating-lc30] Nominatim provider legacy path normalization gate passed.'.PHP_EOL;
