<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$legacyServicePath = $root . '/src/Service/Locator/Normalizer.php';
$legacyInterfacePath = $root . '/src/ServiceInterface/Locator/NormalizerInterface.php';
$canonicalServicePath = $root . '/src/Service/Address/Location/AddressNormalizerService.php';
$canonicalInterfacePath = $root . '/src/ServiceInterface/Address/Location/AddressNormalizerServiceInterface.php';

if (is_file($legacyServicePath)) {
    $errors[] = 'Legacy Normalizer service path must be retired: src/Service/Locator/Normalizer.php';
}

if (is_file($legacyInterfacePath)) {
    $errors[] = 'Legacy NormalizerInterface path must be retired: src/ServiceInterface/Locator/NormalizerInterface.php';
}

if (!is_file($canonicalServicePath)) {
    $errors[] = 'Canonical AddressNormalizerService path is missing.';
} else {
    $contents = (string) file_get_contents($canonicalServicePath);
    if (!str_contains($contents, 'namespace App\Locating\Service\Address\Location;')) {
        $errors[] = 'AddressNormalizerService must use App\Locating\Service\Address\Location namespace.';
    }
    if (!str_contains($contents, 'final class AddressNormalizerService implements AddressNormalizerServiceInterface')) {
        $errors[] = 'AddressNormalizerService must keep service suffix and implement the canonical legacy service interface.';
    }
    if (!str_contains($contents, 'use App\Locating\Model\Location\CanonicalAddress;')) {
        $errors[] = 'AddressNormalizerService must use the canonical App\Locating\Model\Location\CanonicalAddress dependency.';
    }
    if (str_contains($contents, 'use App\Locating\Model\Locator\CanonicalAddress;')) {
        $errors[] = 'AddressNormalizerService still imports the retired Locator CanonicalAddress path.';
    }
    if (str_contains($contents, 'namespace App\Locating\Service\Locator;')) {
        $errors[] = 'AddressNormalizerService must not keep the legacy Smartresponsor service namespace.';
    }
}

if (!is_file($canonicalInterfacePath)) {
    $errors[] = 'Canonical AddressNormalizerServiceInterface path is missing.';
} else {
    $contents = (string) file_get_contents($canonicalInterfacePath);
    if (!str_contains($contents, 'namespace App\Locating\ServiceInterface\Address\Location;')) {
        $errors[] = 'AddressNormalizerServiceInterface must use App\Locating\ServiceInterface\Address\Location namespace.';
    }
    if (!str_contains($contents, 'interface AddressNormalizerServiceInterface')) {
        $errors[] = 'AddressNormalizerServiceInterface must keep the canonical interface class name.';
    }
    if (str_contains($contents, 'namespace App\Locating\ServiceInterface\Locator;')) {
        $errors[] = 'AddressNormalizerServiceInterface must not keep the legacy Smartresponsor interface namespace.';
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
        if (in_array($relative, [
            'src/Service/Locator/Normalizer.php',
            'src/ServiceInterface/Locator/NormalizerInterface.php',
        ], true)) {
            continue;
        }
        $contents = (string) file_get_contents($file->getPathname());
        if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\Normalizer')) {
            $errors[] = 'Legacy Normalizer service FQCN reference remains in ' . $relative;
        }
        if (str_contains($contents, 'App\Locating\ServiceInterface\Provider\Location\Runtime\NormalizerInterface')) {
            $errors[] = 'Legacy NormalizerInterface FQCN reference remains in ' . $relative;
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents(
    $reportDir . '/locating-service-locator-address-normalizer-path-latest.json',
    json_encode([
        'stage' => 'LC-23 Service Locator Address normalizer path normalization',
        'legacy_service_path' => 'src/Service/Locator/Normalizer.php',
        'legacy_interface_path' => 'src/ServiceInterface/Locator/NormalizerInterface.php',
        'canonical_service_path' => 'src/Service/Address/Location/AddressNormalizerService.php',
        'canonical_interface_path' => 'src/ServiceInterface/Address/Location/AddressNormalizerServiceInterface.php',
        'errors' => $errors,
        'warnings' => $warnings,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-23 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-23 error] ' . $error . PHP_EOL);
    }
    exit(1);
}

echo 'LC-23 Service Locator Address normalizer path canon passed.' . PHP_EOL;
