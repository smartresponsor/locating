<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$legacyServicePath = $root . '/src/Service/Locator/AddressReverse.php';
$legacyCanonicalPath = $root . '/src/Service/Address/Location/AddressReverseLegacyService.php';
$legacyInterfacePath = $root . '/src/ServiceInterface/Address/Location/AddressReverseLegacyServiceInterface.php';
$canonicalServicePath = $root . '/src/Service/Address/Location/AddressReverseCapability.php';
$canonicalInterfacePath = $root . '/src/ServiceInterface/Address/Location/AddressReverseCapabilityInterface.php';

if (is_file($legacyServicePath)) {
    $errors[] = 'Legacy AddressReverse service path must be retired: src/Service/Locator/AddressReverse.php';
}

foreach ([
    'src/Service/Address/Location/AddressReverseLegacyService.php' => $legacyCanonicalPath,
    'src/ServiceInterface/Address/Location/AddressReverseLegacyServiceInterface.php' => $legacyInterfacePath,
] as $relativeLegacyPath => $absoluteLegacyPath) {
    if (is_file($absoluteLegacyPath)) {
        $errors[] = 'Retired AddressReverse legacy surface still exists: ' . $relativeLegacyPath;
    }
}

if (!is_file($canonicalServicePath)) {
    $errors[] = 'Canonical AddressReverseCapability path is missing.';
} else {
    $contents = (string) file_get_contents($canonicalServicePath);
    if (!str_contains($contents, 'namespace App\Locating\Service\Address\Location;')) {
        $errors[] = 'AddressReverseCapability must use App\Locating\Service\Address\Location namespace.';
    }
    if (!str_contains($contents, 'final class AddressReverseCapability implements AddressReverseCapabilityInterface')) {
        $errors[] = 'AddressReverseCapability must implement AddressReverseCapabilityInterface.';
    }
    if (!str_contains($contents, 'use App\Locating\ServiceInterface\Provider\Location\AddressReverseProviderInterface;')) {
        $errors[] = 'AddressReverseCapability must depend on the canonical AddressReverseProviderInterface.';
    }
}

if (!is_file($canonicalInterfacePath)) {
    $errors[] = 'Canonical AddressReverseCapabilityInterface path is missing.';
} else {
    $interfaceContents = (string) file_get_contents($canonicalInterfacePath);
    if (!str_contains($interfaceContents, 'namespace App\Locating\ServiceInterface\Address\Location;')) {
        $errors[] = 'AddressReverseCapabilityInterface must use the canonical ServiceInterface namespace.';
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
        if ('src/Service/Locator/AddressReverse.php' === $relative) {
            continue;
        }
        $contents = (string) file_get_contents($file->getPathname());
        if (str_contains($contents, 'App\Locating\\Service\\Provider\\Location\\Runtime\\AddressReverse')) {
            $errors[] = 'Legacy AddressReverse service FQCN reference remains in ' . $relative;
        }
        if (str_contains($contents, 'new AddressReverse(')) {
            $warnings[] = 'Short AddressReverse construction should be reviewed in ' . $relative;
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents(
    $reportDir . '/locating-service-locator-address-reverse-path-latest.json',
    json_encode([
        'stage' => 'LC-24 Service Locator AddressReverse path normalization',
        'legacy_service_path' => 'src/Service/Locator/AddressReverse.php',
        'canonical_service_path' => 'src/Service/Address/Location/AddressReverseCapability.php',
        'canonical_namespace' => 'App\Locating\\Service\\Address\\Location',
        'canonical_class' => 'AddressReverseCapability',
        'errors' => $errors,
        'warnings' => $warnings,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-24 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-24 error] ' . $error . PHP_EOL);
    }
    exit(1);
}

echo 'LC-24 Service Locator AddressReverse path canon passed.' . PHP_EOL;
