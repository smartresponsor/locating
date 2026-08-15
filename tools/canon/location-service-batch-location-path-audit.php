<?php

declare(strict_types=1);

/*
 * Locating LC-10 canonical service path audit.
 *
 * This gate protects the App-owned Batch service slice after the physical path
 * normalization:
 *
 *   App\Locating\Service\Batch\Location\*          => src/Service/Batch/Location/*
 *   App\Locating\ServiceInterface\Batch\Location\* => src/ServiceInterface/Batch/Location/*
 *
 * The gate is intentionally narrow. Smartresponsor legacy services and the
 * broader provider/observability families are tracked by separate waves.
 */

$projectRoot = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$canonicalFiles = [
    'src/Service/Batch/Location/AddressBatchJobFactory.php' => 'App\Locating\\Service\\Batch\\Location',
    'src/Service/Batch/Location/LocationAddressBatchService.php' => 'App\Locating\\Service\\Batch\\Location',
    'src/Service/Batch/Location/LocationAddressBatchServiceMetricDecorator.php' => 'App\Locating\\Service\\Batch\\Location',
    'src/ServiceInterface/Batch/Location/AddressBatchJobFactoryInterface.php' => 'App\Locating\\ServiceInterface\\Batch\\Location',
    'src/ServiceInterface/Batch/Location/LocationAddressBatchServiceInterface.php' => 'App\Locating\\ServiceInterface\\Batch\\Location',
];

$retiredFiles = [
    'src/Service/Batch/AddressBatchJobFactory.php',
    'src/Service/Batch/LocationAddressBatchService.php',
    'src/Service/Batch/LocationAddressBatchServiceMetricDecorator.php',
    'src/ServiceInterface/Batch/AddressBatchJobFactoryInterface.php',
    'src/ServiceInterface/Batch/LocationAddressBatchServiceInterface.php',
];

foreach ($canonicalFiles as $relativePath => $expectedNamespace) {
    $absolutePath = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($absolutePath)) {
        $errors[] = 'Missing canonical LC-10 file: ' . $relativePath;
        continue;
    }

    $contents = (string) file_get_contents($absolutePath);

    if (!str_contains($contents, 'namespace ' . $expectedNamespace . ';')) {
        $errors[] = sprintf('Namespace mismatch in %s; expected %s', $relativePath, $expectedNamespace);
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
        $errors[] = 'Retired pre-LC-10 service path still exists: ' . $relativePath;
    }
}

$reportDir = $projectRoot . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    $errors[] = 'Unable to create report directory: report';
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-10',
    'scope' => 'App-owned Batch service path normalization',
    'canonical_files' => array_keys($canonicalFiles),
    'retired_files' => $retiredFiles,
    'errors' => $errors,
    'warnings' => $warnings,
    'generated_at' => gmdate('c'),
];

if (is_dir($reportDir)) {
    file_put_contents($reportDir . DIRECTORY_SEPARATOR . 'locating-service-batch-location-path-audit-latest.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);
}

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-10 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-10 error] ' . $error . PHP_EOL);
    }

    exit(1);
}

fwrite(STDOUT, 'LC-10 service batch/location path audit passed.' . PHP_EOL);
