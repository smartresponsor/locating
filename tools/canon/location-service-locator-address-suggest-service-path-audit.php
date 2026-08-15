<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$legacyPath = $root . '/src/Service/Locator/AddressSuggest.php';
$legacyCanonicalPath = $root . '/src/Service/Address/Location/AddressSuggestLegacyService.php';
$legacyInterfacePath = $root . '/src/ServiceInterface/Address/Location/AddressSuggestLegacyServiceInterface.php';
$canonicalPath = $root . '/src/Service/Address/Location/AddressSuggestCapability.php';
$canonicalInterfacePath = $root . '/src/ServiceInterface/Address/Location/AddressSuggestCapabilityInterface.php';

if (is_file($legacyPath)) {
    $errors[] = 'Legacy AddressSuggest path must be retired: src/Service/Locator/AddressSuggest.php';
}

foreach ([
    'src/Service/Address/Location/AddressSuggestLegacyService.php' => $legacyCanonicalPath,
    'src/ServiceInterface/Address/Location/AddressSuggestLegacyServiceInterface.php' => $legacyInterfacePath,
] as $relativeLegacyPath => $absoluteLegacyPath) {
    if (is_file($absoluteLegacyPath)) {
        $errors[] = 'Retired AddressSuggest legacy surface still exists: ' . $relativeLegacyPath;
    }
}

if (!is_file($canonicalPath)) {
    $errors[] = 'Canonical AddressSuggestCapability path is missing: src/Service/Address/Location/AddressSuggestCapability.php';
} else {
    $contents = (string) file_get_contents($canonicalPath);
    if (!str_contains($contents, 'namespace App\Locating\Service\Address\Location;')) {
        $errors[] = 'AddressSuggestCapability must use App\Locating\Service\Address\Location namespace.';
    }
    if (!preg_match('/final\s+class\s+AddressSuggestCapability\s+implements\s+AddressSuggestCapabilityInterface/', $contents)) {
        $errors[] = 'AddressSuggestCapability must implement AddressSuggestCapabilityInterface.';
    }
}

if (!is_file($canonicalInterfacePath)) {
    $errors[] = 'Canonical AddressSuggestCapabilityInterface path is missing.';
} else {
    $interfaceContents = (string) file_get_contents($canonicalInterfacePath);
    if (!str_contains($interfaceContents, 'namespace App\Locating\ServiceInterface\Address\Location;')) {
        $errors[] = 'AddressSuggestCapabilityInterface must use the canonical ServiceInterface namespace.';
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
        if ('src/Service/Locator/AddressSuggest.php' === $relative) {
            continue;
        }
        $contents = (string) file_get_contents($file->getPathname());
        if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\AddressSuggest')) {
            $errors[] = 'Legacy AddressSuggest FQCN reference remains in ' . $relative;
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents(
    $reportDir . '/locating-service-locator-address-suggest-service-path-latest.json',
    json_encode([
        'stage' => 'LC-19 Service Locator AddressSuggest service path normalization',
        'legacy_path' => 'src/Service/Locator/AddressSuggest.php',
        'canonical_path' => 'src/Service/Address/Location/AddressSuggestCapability.php',
        'errors' => $errors,
        'warnings' => $warnings,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-19 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-19 error] ' . $error . PHP_EOL);
    }
    exit(1);
}

echo 'LC-19 Service Locator AddressSuggest service path canon passed.' . PHP_EOL;
