<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacy = $root.'/src/Service/Locator/ProviderKeyRotation.php';
$target = $root.'/src/Service/Provider/Location/ProviderKeyRotationService.php';
$errors = [];

if (is_file($legacy)) {
    $errors[] = 'Legacy ProviderKeyRotation path still exists: src/Service/Locator/ProviderKeyRotation.php';
}

if (!is_file($target)) {
    $errors[] = 'Canonical ProviderKeyRotationService path is missing.';
} else {
    $contents = (string) file_get_contents($target);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'ProviderKeyRotationService namespace is not canonical.';
    }
    if (!str_contains($contents, 'final class ProviderKeyRotationService')) {
        $errors[] = 'ProviderKeyRotationService class declaration is missing.';
    }
    if (!str_contains($contents, 'ProviderKeyRotationInterface')) {
        $errors[] = 'ProviderKeyRotationService must keep the bridge legacy interface contract.';
    }
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-38] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[LC-38] ProviderKeyRotation legacy path normalization is canonical.'.PHP_EOL;
