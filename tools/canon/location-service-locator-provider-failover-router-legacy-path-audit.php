<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacy = $root.'/src/Service/Locator/ProviderRouter.php';
$target = $root.'/src/Service/Provider/Location/ProviderFailoverRouterLegacyService.php';
$errors = [];

if (is_file($legacy)) {
    $errors[] = 'Legacy ProviderRouter path still exists: src/Service/Locator/ProviderRouter.php';
}

if (!is_file($target)) {
    $errors[] = 'Canonical ProviderFailoverRouterLegacyService path is missing.';
} else {
    $contents = (string) file_get_contents($target);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'ProviderFailoverRouterLegacyService namespace is not canonical.';
    }
    if (!str_contains($contents, 'final class ProviderFailoverRouterLegacyService')) {
        $errors[] = 'ProviderFailoverRouterLegacyService class declaration is missing.';
    }
    if (!str_contains($contents, 'ProviderRouterLegacyInterface')) {
        $errors[] = 'ProviderFailoverRouterLegacyService must keep the bridge legacy interface contract.';
    }
    if (str_contains($contents, 'final class ProviderRouter ')) {
        $errors[] = 'ProviderFailoverRouterLegacyService must not keep the generic ProviderRouter class name.';
    }
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-40] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[LC-40] ProviderFailoverRouter legacy path normalization is canonical.'.PHP_EOL;
