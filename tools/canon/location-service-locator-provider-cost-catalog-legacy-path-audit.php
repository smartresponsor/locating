<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacy = $root.'/src/Service/Locator/ProviderCostCatalog.php';
$target = $root.'/src/Service/Provider/Location/ProviderCostCatalogService.php';
$errors = [];

if (is_file($legacy)) {
    $errors[] = 'Legacy ProviderCostCatalog path still exists: src/Service/Locator/ProviderCostCatalog.php';
}

if (!is_file($target)) {
    $errors[] = 'Canonical ProviderCostCatalogService path is missing.';
} else {
    $contents = (string) file_get_contents($target);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'ProviderCostCatalogService namespace is not canonical.';
    }
    if (!str_contains($contents, 'final class ProviderCostCatalogService')) {
        $errors[] = 'ProviderCostCatalogService class declaration is missing.';
    }
    if (!str_contains($contents, 'ProviderCostCatalogInterface')) {
        $errors[] = 'ProviderCostCatalogService must keep the bridge legacy interface contract.';
    }
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-39] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[LC-39] ProviderCostCatalog legacy path normalization is canonical.'.PHP_EOL;
