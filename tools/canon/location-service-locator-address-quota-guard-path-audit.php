<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$legacyServicePath = $root . '/src/Service/Locator/AddressQuotaGuard.php';
$canonicalServicePath = $root . '/src/Service/Address/Location/AddressQuotaGuard.php';
$consumerPath = $root . '/src/Service/Http/Location/LocationQuotaGuardBackend.php';

if (is_file($legacyServicePath)) {
    $errors[] = 'Legacy AddressQuotaGuard service path must be retired: src/Service/Locator/AddressQuotaGuard.php';
}

if (!is_file($canonicalServicePath)) {
    $errors[] = 'Canonical AddressQuotaGuard path is missing: src/Service/Address/Location/AddressQuotaGuard.php';
} else {
    $contents = (string) file_get_contents($canonicalServicePath);
    if (!str_contains($contents, 'namespace App\Locating\Service\Address\Location;')) {
        $errors[] = 'AddressQuotaGuard must use App\Locating\Service\Address\Location namespace.';
    }
    if (!str_contains($contents, 'final class AddressQuotaGuard implements AddressQuotaGuardServiceInterface')) {
        $errors[] = 'AddressQuotaGuard must implement the canonical AddressQuotaGuardServiceInterface contract.';
    }
    if (!str_contains($contents, 'public const OPERATION_GEOCODE')) {
        $errors[] = 'AddressQuotaGuard must preserve OPERATION_GEOCODE constant for HTTP backend consumers.';
    }
    if (!str_contains($contents, 'public const OPERATION_SUGGEST')) {
        $errors[] = 'AddressQuotaGuard must preserve OPERATION_SUGGEST constant for HTTP backend consumers.';
    }
    if (str_contains($contents, 'namespace App\Locating\Service\Locator;')) {
        $errors[] = 'AddressQuotaGuard must not keep the legacy Smartresponsor service namespace.';
    }
}

if (!is_file($consumerPath)) {
    $errors[] = 'Expected canonical HTTP quota guard backend consumer was not found: src/Service/Http/Location/LocationQuotaGuardBackend.php';
} else {
    $contents = (string) file_get_contents($consumerPath);
    if (!str_contains($contents, 'use App\Locating\Service\Address\Location\AddressQuotaGuard as InnerAddressQuotaGuard;')) {
        $errors[] = 'LocationQuotaGuardBackend must import the canonical AddressQuotaGuard FQCN.';
    }
    if (str_contains($contents, 'use App\Locating\Service\Provider\Location\Runtime\AddressQuotaGuard as InnerAddressQuotaGuard;')) {
        $errors[] = 'LocationQuotaGuardBackend still imports the retired AddressQuotaGuard FQCN.';
    }
}

$src = $root . '/src';
if (is_dir($src)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || 'php' !== $file->getExtension()) {
            continue;
        }
        $relative = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
        if ('src/Service/Locator/AddressQuotaGuard.php' === $relative) {
            continue;
        }
        $contents = (string) file_get_contents($file->getPathname());
        if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\AddressQuotaGuard')) {
            $errors[] = 'Legacy AddressQuotaGuard FQCN reference remains in ' . $relative;
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents(
    $reportDir . '/locating-service-locator-address-quota-guard-path-latest.json',
    json_encode([
        'stage' => 'LC-22 Service Locator AddressQuotaGuard path normalization',
        'legacy_service_path' => 'src/Service/Locator/AddressQuotaGuard.php',
        'canonical_service_path' => 'src/Service/Address/Location/AddressQuotaGuard.php',
        'updated_consumer' => 'src/Service/Http/Location/LocationQuotaGuardBackend.php',
        'errors' => $errors,
        'warnings' => $warnings,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-22 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-22 error] ' . $error . PHP_EOL);
    }
    exit(1);
}

echo 'LC-22 Service Locator AddressQuotaGuard path canon passed.' . PHP_EOL;
