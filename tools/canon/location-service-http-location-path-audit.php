<?php

declare(strict_types=1);

/*
 * Locating LC-09 canonical service path audit.
 *
 * This gate protects the App-owned Http service slice after the
 * physical path normalization:
 *
 *   App\Locating\Service\Http\Location\*          => src/Service/Http/Location/*
 *   App\Locating\ServiceInterface\Http\Location\* => src/ServiceInterface/Http/Location/*
 *
 * It is intentionally narrow. The legacy Smartresponsor service cluster is
 * tracked by the broader service-family audit and must not be mixed into this
 * small path-normalization wave.
 */

$projectRoot = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$canonicalFiles = [
    'src/Service/Http/Location/LocationAddressReverseService.php' => 'App\Locating\\Service\\Http\\Location',
    'src/Service/Http/Location/LocationAddressSuggestService.php' => 'App\Locating\\Service\\Http\\Location',
    'src/Service/Http/Location/LocationQuotaGuard.php' => 'App\Locating\\Service\\Http\\Location',
    'src/Service/Http/Location/LocationViewFactory.php' => 'App\Locating\\Service\\Http\\Location',
    'src/Service/Http/Location/LocationQuotaGuardBackend.php' => 'App\Locating\\Service\\Http\\Location',
    'src/ServiceInterface/Http/Location/LocationAddressReverseServiceInterface.php' => 'App\Locating\\ServiceInterface\\Http\\Location',
    'src/ServiceInterface/Http/Location/LocationAddressSuggestServiceInterface.php' => 'App\Locating\\ServiceInterface\\Http\\Location',
    'src/ServiceInterface/Http/Location/LocationQuotaGuardBackendInterface.php' => 'App\Locating\\ServiceInterface\\Http\\Location',
    'src/ServiceInterface/Http/Location/LocationQuotaGuardInterface.php' => 'App\Locating\\ServiceInterface\\Http\\Location',
    'src/ServiceInterface/Http/Location/LocationViewFactoryInterface.php' => 'App\Locating\\ServiceInterface\\Http\\Location',
];

$retiredFiles = [
    'src/Service/Http/LocationAddressReverseService.php',
    'src/Service/Http/LocationAddressSuggestService.php',
    'src/Service/Http/LocationQuotaGuard.php',
    'src/Service/Http/LocationViewFactory.php',
    'src/Service/Http/SmartresponsorLocationQuotaGuardBackend.php',
    'src/Service/Http/Location/SmartresponsorLocationQuotaGuardBackend.php',
    'src/ServiceInterface/Http/LocationAddressReverseServiceInterface.php',
    'src/ServiceInterface/Http/LocationAddressSuggestServiceInterface.php',
    'src/ServiceInterface/Http/LocationQuotaGuardBackendInterface.php',
    'src/ServiceInterface/Http/LocationQuotaGuardInterface.php',
    'src/ServiceInterface/Http/LocationViewFactoryInterface.php',
];

foreach ($canonicalFiles as $relativePath => $expectedNamespace) {
    $absolutePath = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($absolutePath)) {
        $errors[] = 'Missing canonical LC-09 file: ' . $relativePath;
        continue;
    }

    $contents = (string) file_get_contents($absolutePath);

    if (!str_contains($contents, 'namespace ' . $expectedNamespace . ';')) {
        $errors[] = sprintf(
            'Namespace mismatch in %s; expected %s',
            $relativePath,
            $expectedNamespace
        );
    }

    if (str_starts_with($relativePath, 'src/ServiceInterface/') && !str_contains($contents, 'interface ')) {
        $errors[] = 'ServiceInterface file is not an interface: ' . $relativePath;
    }

    if (str_starts_with($relativePath, 'src/Service/') && !str_contains($contents, 'final class ')) {
        $warnings[] = 'Service file is not a final class: ' . $relativePath;
    }
}

foreach ($retiredFiles as $relativePath) {
    $absolutePath = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (is_file($absolutePath)) {
        $errors[] = 'Retired pre-LC-09 service path still exists: ' . $relativePath;
    }
}

$reportDir = $projectRoot . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    $errors[] = 'Unable to create report directory: report';
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-09',
    'scope' => 'App-owned Http service path normalization',
    'canonical_files' => array_keys($canonicalFiles),
    'retired_files' => $retiredFiles,
    'errors' => $errors,
    'warnings' => $warnings,
    'generated_at' => gmdate('c'),
];

if (is_dir($reportDir)) {
    file_put_contents(
        $reportDir . DIRECTORY_SEPARATOR . 'locating-service-http-location-path-audit-latest.json',
        json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
    );
}

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-09 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-09 error] ' . $error . PHP_EOL);
    }

    exit(1);
}

fwrite(STDOUT, 'LC-09 service http/location path audit passed.' . PHP_EOL);
