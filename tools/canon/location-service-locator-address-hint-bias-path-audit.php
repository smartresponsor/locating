<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$legacyServicePath = $root . '/src/Service/Locator/AddressHintBias.php';
$legacyInterfacePath = $root . '/src/ServiceInterface/Locator/AddressHintBiasInterface.php';
$canonicalServicePath = $root . '/src/Service/Address/Location/AddressHintBiasService.php';
$canonicalInterfacePath = $root . '/src/ServiceInterface/Address/Location/AddressHintBiasServiceInterface.php';
$consumerPath = $root . '/src/Service/Provider/Location/Runtime/Routing/AdaptiveOrdering.php';

if (is_file($legacyServicePath)) {
    $errors[] = 'Legacy AddressHintBias service path must be retired: src/Service/Locator/AddressHintBias.php';
}
if (is_file($legacyInterfacePath)) {
    $errors[] = 'Legacy AddressHintBias interface path must be retired: src/ServiceInterface/Locator/AddressHintBiasInterface.php';
}

if (!is_file($canonicalServicePath)) {
    $errors[] = 'Canonical AddressHintBiasService path is missing: src/Service/Address/Location/AddressHintBiasService.php';
} else {
    $contents = (string) file_get_contents($canonicalServicePath);
    if (!str_contains($contents, 'namespace App\Locating\Service\Address\Location;')) {
        $errors[] = 'AddressHintBiasService must use App\Locating\Service\Address\Location namespace.';
    }
    if (!str_contains($contents, 'use App\Locating\ServiceInterface\Address\Location\AddressHintBiasServiceInterface;')) {
        $errors[] = 'AddressHintBiasService must import the canonical mirrored interface.';
    }
    if (!preg_match('/final\s+class\s+AddressHintBiasService\s+implements\s+AddressHintBiasServiceInterface/', $contents)) {
        $errors[] = 'AddressHintBiasService must keep the canonical Service suffix and mirrored interface.';
    }
    if (str_contains($contents, 'namespace App\Locating\Service\Locator;')) {
        $errors[] = 'AddressHintBiasService must not keep the legacy Smartresponsor service namespace.';
    }
}

if (!is_file($canonicalInterfacePath)) {
    $errors[] = 'Canonical AddressHintBiasServiceInterface path is missing: src/ServiceInterface/Address/Location/AddressHintBiasServiceInterface.php';
} else {
    $contents = (string) file_get_contents($canonicalInterfacePath);
    if (!str_contains($contents, 'namespace App\Locating\ServiceInterface\Address\Location;')) {
        $errors[] = 'AddressHintBiasServiceInterface must use App\Locating\ServiceInterface\Address\Location namespace.';
    }
    if (!preg_match('/interface\s+AddressHintBiasServiceInterface/', $contents)) {
        $errors[] = 'AddressHintBiasServiceInterface must keep the canonical ServiceInterface suffix.';
    }
    if (str_contains($contents, 'namespace App\Locating\InfrastructureInterface\Provider\Location;')) {
        $errors[] = 'AddressHintBiasServiceInterface must not keep the legacy Smartresponsor domain namespace.';
    }
}

if (is_file($consumerPath)) {
    $contents = (string) file_get_contents($consumerPath);
    if (!str_contains($contents, 'use App\Locating\Service\Address\Location\AddressHintBiasService;')) {
        $errors[] = 'AdaptiveOrdering must import App\Locating\Service\Address\Location\AddressHintBiasService.';
    }
    if (!str_contains($contents, 'private AddressHintBiasService $bias,')) {
        $errors[] = 'AdaptiveOrdering must type the bias dependency as AddressHintBiasService.';
    }
    if (str_contains($contents, 'private AddressHintBias $bias,')) {
        $errors[] = 'AdaptiveOrdering still references the retired AddressHintBias class name.';
    }
} else {
    $warnings[] = 'AdaptiveOrdering consumer path was not found during LC-21 audit.';
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
            'src/Service/Locator/AddressHintBias.php',
            'src/ServiceInterface/Locator/AddressHintBiasInterface.php',
        ], true)) {
            continue;
        }
        $contents = (string) file_get_contents($file->getPathname());
        if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\AddressHintBias')) {
            $errors[] = 'Legacy AddressHintBias FQCN reference remains in ' . $relative;
        }
        if (str_contains($contents, 'App\Locating\ServiceInterface\Provider\Location\Runtime\AddressHintBiasInterface')) {
            $errors[] = 'Legacy AddressHintBiasInterface FQCN reference remains in ' . $relative;
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents(
    $reportDir . '/locating-service-locator-address-hint-bias-path-latest.json',
    json_encode([
        'stage' => 'LC-21 Service Locator AddressHintBias path normalization',
        'legacy_service_path' => 'src/Service/Locator/AddressHintBias.php',
        'canonical_service_path' => 'src/Service/Address/Location/AddressHintBiasService.php',
        'legacy_interface_path' => 'src/ServiceInterface/Locator/AddressHintBiasInterface.php',
        'canonical_interface_path' => 'src/ServiceInterface/Address/Location/AddressHintBiasServiceInterface.php',
        'updated_consumer' => 'src/Service/Provider/Location/Runtime/Routing/AdaptiveOrdering.php',
        'errors' => $errors,
        'warnings' => $warnings,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-21 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-21 error] ' . $error . PHP_EOL);
    }
    exit(1);
}

echo 'LC-21 Service Locator AddressHintBias path canon passed.' . PHP_EOL;
