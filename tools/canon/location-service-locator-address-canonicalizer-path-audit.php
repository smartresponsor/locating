<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$legacyPath = $root . '/src/Service/Locator/AddressCanonicalizer.php';
$canonicalPath = $root . '/src/Service/Address/Location/AddressCanonicalizerService.php';

if (is_file($legacyPath)) {
    $errors[] = 'Legacy AddressCanonicalizer path must be retired: src/Service/Locator/AddressCanonicalizer.php';
}

if (!is_file($canonicalPath)) {
    $errors[] = 'Canonical AddressCanonicalizerService path is missing: src/Service/Address/Location/AddressCanonicalizerService.php';
} else {
    $contents = (string) file_get_contents($canonicalPath);
    if (!str_contains($contents, 'namespace App\Locating\Service\Address\Location;')) {
        $errors[] = 'AddressCanonicalizerService must use App\Locating\Service\Address\Location namespace.';
    }
    if (!preg_match('/final\s+class\s+AddressCanonicalizerService\s+implements\s+AddressCanonicalizerInterface/', $contents)) {
        $errors[] = 'AddressCanonicalizerService must keep the canonical Service suffix and legacy helper contract.';
    }
    if (str_contains($contents, 'namespace App\Locating\Service\Locator;')) {
        $errors[] = 'AddressCanonicalizerService must not keep the legacy Smartresponsor service namespace.';
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
        if ('src/Service/Locator/AddressCanonicalizer.php' === $relative) {
            continue;
        }
        $contents = (string) file_get_contents($file->getPathname());
        if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\AddressCanonicalizer')) {
            $errors[] = 'Legacy AddressCanonicalizer FQCN reference remains in ' . $relative;
        }
    }
}

$reportDir = $root . '/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents(
    $reportDir . '/locating-service-locator-address-canonicalizer-path-latest.json',
    json_encode([
        'stage' => 'LC-20 Service Locator AddressCanonicalizer path normalization',
        'legacy_path' => 'src/Service/Locator/AddressCanonicalizer.php',
        'canonical_path' => 'src/Service/Address/Location/AddressCanonicalizerService.php',
        'errors' => $errors,
        'warnings' => $warnings,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-20 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-20 error] ' . $error . PHP_EOL);
    }
    exit(1);
}

echo 'LC-20 Service Locator AddressCanonicalizer path canon passed.' . PHP_EOL;
