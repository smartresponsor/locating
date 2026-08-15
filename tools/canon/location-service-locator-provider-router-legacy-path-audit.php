<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacyPath = $root.'/src/Service/Locator/Provider/ProviderRouter.php';
$canonicalPath = $root.'/src/Service/Provider/Location/ProviderRouterService.php';
$kernelPath = $root.'/src/Infrastructure/Locator/Http/Kernel.php';

$errors = [];

if (is_file($legacyPath)) {
    $errors[] = 'Legacy ProviderRouter path still exists: src/Service/Locator/Provider/ProviderRouter.php';
}

if (!is_file($canonicalPath)) {
    $errors[] = 'Canonical provider router path is missing: src/Service/Provider/Location/ProviderRouterService.php';
} else {
    $code = file_get_contents($canonicalPath);
    if (!str_contains($code, 'namespace App\Locating\\Service\\Provider\\Location;')) {
        $errors[] = 'Canonical provider router must use App\Locating\\Service\\Provider\\Location namespace.';
    }
    if (!preg_match('/class\\s+ProviderRouterService\\b/', $code)) {
        $errors[] = 'Canonical provider router class must be ProviderRouterService.';
    }
    if (str_contains($code, 'namespace App\Locating\\Service\\Provider\\Location\\Runtime\\Provider;')) {
        $errors[] = 'Canonical provider router still contains legacy Smartresponsor provider namespace.';
    }
    foreach (['HereProviderService', 'MapboxProviderService', 'NominatimProviderService', 'ProviderRankerService'] as $symbol) {
        if (!str_contains($code, $symbol)) {
            $errors[] = 'Canonical provider router is missing dependency/reference: '.$symbol;
        }
    }
}

if (!is_file($kernelPath)) {
    $errors[] = 'Kernel not found for provider router reference check.';
} else {
    $kernel = file_get_contents($kernelPath);
    if (!str_contains($kernel, 'use App\Locating\\Service\\Provider\\Location\\ProviderRouterService;')) {
        $errors[] = 'Kernel must import ProviderRouterService.';
    }
    if (str_contains($kernel, 'use App\Locating\\Service\\Provider\\Location\\Runtime\\Provider\\ProviderRouter;')) {
        $errors[] = 'Kernel still imports legacy ProviderRouter.';
    }
    if (str_contains($kernel, 'new ProviderRouter(')) {
        $errors[] = 'Kernel still instantiates legacy ProviderRouter.';
    }
    if (!str_contains($kernel, 'new ProviderRouterService(')) {
        $errors[] = 'Kernel must instantiate ProviderRouterService.';
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir.'/locating-lc31-service-locator-provider-router-legacy-path-latest.json',
    json_encode([
        'stage' => 'LC-31 service locator provider router legacy path normalization',
        'legacy_path' => 'src/Service/Locator/Provider/ProviderRouter.php',
        'canonical_path' => 'src/Service/Provider/Location/ProviderRouterService.php',
        'kernel_path' => 'src/Infrastructure/Locator/Http/Kernel.php',
        'errors' => $errors,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL
);

if ($errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[locating-lc31] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[locating-lc31] Provider router legacy path normalization gate passed.'.PHP_EOL;
