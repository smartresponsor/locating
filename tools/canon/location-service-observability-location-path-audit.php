<?php

declare(strict_types=1);

/*
 * Locating LC-11 canonical service path audit.
 *
 * This gate protects the App-owned Observability service slice after the
 * physical path normalization:
 *
 *   App\Locating\Service\Observability\Location\*          => src/Service/Observability/Location/*
 *   App\Locating\ServiceInterface\Observability\Location\* => src/ServiceInterface/Observability/Location/*
 *
 * The gate is intentionally narrow. Smartresponsor legacy services and the
 * broader provider/bridge families remain separate retirement tracks.
 */

$projectRoot = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$canonicalFiles = [
    'src/Service/Observability/Location/LocationMetricsExportService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceAcknowledgementService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceAuditService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceCatalogService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceExecutionService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceExplanationService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceMetricsExportService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceRecommendationService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceRemediationPlanService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationProviderGovernanceReportService.php' => 'App\Locating\Service\Observability\Location',
    'src/Service/Observability/Location/LocationStatusReportService.php' => 'App\Locating\Service\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationMetricsExportServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceAcknowledgementServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceAuditServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceCatalogServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceExecutionServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceExplanationServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceMetricsExportServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceRecommendationServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceRemediationPlanServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationProviderGovernanceReportServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
    'src/ServiceInterface/Observability/Location/LocationStatusReportServiceInterface.php' => 'App\Locating\ServiceInterface\Observability\Location',
];

$retiredFiles = [
    'src/Service/Observability/LocationMetricsExportService.php',
    'src/Service/Observability/LocationProviderGovernanceAcknowledgementService.php',
    'src/Service/Observability/LocationProviderGovernanceAuditService.php',
    'src/Service/Observability/LocationProviderGovernanceCatalogService.php',
    'src/Service/Observability/LocationProviderGovernanceExecutionService.php',
    'src/Service/Observability/LocationProviderGovernanceExplanationService.php',
    'src/Service/Observability/LocationProviderGovernanceMetricsExportService.php',
    'src/Service/Observability/LocationProviderGovernanceRecommendationService.php',
    'src/Service/Observability/LocationProviderGovernanceRemediationPlanService.php',
    'src/Service/Observability/LocationProviderGovernanceReportService.php',
    'src/Service/Observability/LocationStatusReportService.php',
    'src/ServiceInterface/Observability/LocationMetricsExportServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceAcknowledgementServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceAuditServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceCatalogServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceExecutionServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceExplanationServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceMetricsExportServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceRecommendationServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceRemediationPlanServiceInterface.php',
    'src/ServiceInterface/Observability/LocationProviderGovernanceReportServiceInterface.php',
    'src/ServiceInterface/Observability/LocationStatusReportServiceInterface.php',
];

foreach ($canonicalFiles as $relativePath => $expectedNamespace) {
    $absolutePath = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($absolutePath)) {
        $errors[] = 'Missing canonical LC-11 file: ' . $relativePath;
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
        $errors[] = 'Retired pre-LC-11 service path still exists: ' . $relativePath;
    }
}

$reportDir = $projectRoot . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    $errors[] = 'Unable to create report directory: report';
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-11',
    'scope' => 'App-owned Observability service path normalization',
    'canonical_files' => array_keys($canonicalFiles),
    'retired_files' => $retiredFiles,
    'errors' => $errors,
    'warnings' => $warnings,
    'generated_at' => gmdate('c'),
];

if (is_dir($reportDir)) {
    file_put_contents($reportDir . DIRECTORY_SEPARATOR . 'locating-service-observability-location-path-audit-latest.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL);
}

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-11 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-11 error] ' . $error . PHP_EOL);
    }

    exit(1);
}

fwrite(STDOUT, 'LC-11 service observability/location path audit passed.' . PHP_EOL);
