<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacy = $root.'/src/Service/Locator/ProviderKeyVault.php';
$target = $root.'/src/Service/Provider/Location/ProviderKeyVaultService.php';
$errors = [];

if (is_file($legacy)) {
    $errors[] = 'Legacy ProviderKeyVault path still exists: src/Service/Locator/ProviderKeyVault.php';
}

if (!is_file($target)) {
    $errors[] = 'Canonical ProviderKeyVaultService path is missing.';
} else {
    $contents = (string) file_get_contents($target);
    if (!str_contains($contents, 'namespace App\Locating\\Service\\Provider\\Location;')) {
        $errors[] = 'ProviderKeyVaultService namespace is not canonical.';
    }
    if (!str_contains($contents, 'final class ProviderKeyVaultService')) {
        $errors[] = 'ProviderKeyVaultService class declaration is missing.';
    }
    if (!str_contains($contents, 'ProviderKeyVaultInterface')) {
        $errors[] = 'ProviderKeyVaultService must keep the bridge legacy interface contract.';
    }
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-37] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[LC-37] ProviderKeyVault legacy path normalization is canonical.'.PHP_EOL;
