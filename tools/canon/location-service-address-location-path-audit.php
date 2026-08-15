<?php

declare(strict_types=1);

/*
 * Locating LC-08 canonical service path audit.
 *
 * This gate protects the App-owned Address service slice after the
 * physical path normalization:
 *
 *   App\Locating\Service\Address\Location\*          => src/Service/Address/Location/*
 *   App\Locating\ServiceInterface\Address\Location\* => src/ServiceInterface/Address/Location/*
 *
 * It is intentionally narrow. The legacy Smartresponsor service cluster is
 * tracked by the broader service-family audit and must not be mixed into this
 * small path-normalization wave.
 */

$projectRoot = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$canonicalFiles = [
    'src/Service/Address/Location/AddressNormalizer.php' => 'App\Locating\\Service\\Address\\Location',
    'src/Service/Address/Location/AddressParser.php' => 'App\Locating\\Service\\Address\\Location',
    'src/Service/Address/Location/AddressPipeline.php' => 'App\Locating\\Service\\Address\\Location',
    'src/Service/Address/Location/AddressReverseCapability.php' => 'App\Locating\\Service\\Address\\Location',
    'src/Service/Address/Location/AddressSuggestCapability.php' => 'App\Locating\\Service\\Address\\Location',
    'src/Service/Address/Location/AddressValidator.php' => 'App\Locating\\Service\\Address\\Location',
    'src/Service/Address/Location/LocationResultFactory.php' => 'App\Locating\\Service\\Address\\Location',
    'src/ServiceInterface/Address/Location/AddressNormalizerInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
    'src/ServiceInterface/Address/Location/AddressParserInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
    'src/ServiceInterface/Address/Location/AddressPipelineInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
    'src/ServiceInterface/Address/Location/AddressReverseCapabilityInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
    'src/ServiceInterface/Address/Location/AddressSuggestCapabilityInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
    'src/ServiceInterface/Address/Location/AddressValidatorInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
    'src/ServiceInterface/Address/Location/LocationResultFactoryInterface.php' => 'App\Locating\\ServiceInterface\\Address\\Location',
];

$retiredFiles = [
    'src/Service/Address/AddressNormalizer.php',
    'src/Service/Address/AddressParser.php',
    'src/Service/Address/AddressPipeline.php',
    'src/Service/Address/AddressReverseCapability.php',
    'src/Service/Address/AddressSuggestCapability.php',
    'src/Service/Address/AddressValidator.php',
    'src/Service/Address/LocationResultFactory.php',
    'src/ServiceInterface/Address/AddressNormalizerInterface.php',
    'src/ServiceInterface/Address/AddressParserInterface.php',
    'src/ServiceInterface/Address/AddressPipelineInterface.php',
    'src/ServiceInterface/Address/AddressReverseCapabilityInterface.php',
    'src/ServiceInterface/Address/AddressSuggestCapabilityInterface.php',
    'src/ServiceInterface/Address/AddressValidatorInterface.php',
    'src/ServiceInterface/Address/LocationResultFactoryInterface.php',
];

foreach ($canonicalFiles as $relativePath => $expectedNamespace) {
    $absolutePath = $projectRoot . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);

    if (!is_file($absolutePath)) {
        $errors[] = 'Missing canonical LC-08 file: ' . $relativePath;
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
        $errors[] = 'Retired pre-LC-08 service path still exists: ' . $relativePath;
    }
}

$reportDir = $projectRoot . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    $errors[] = 'Unable to create report directory: report';
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-08',
    'scope' => 'App-owned Address service path normalization',
    'canonical_files' => array_keys($canonicalFiles),
    'retired_files' => $retiredFiles,
    'errors' => $errors,
    'warnings' => $warnings,
    'generated_at' => gmdate('c'),
];

if (is_dir($reportDir)) {
    file_put_contents(
        $reportDir . DIRECTORY_SEPARATOR . 'locating-service-address-location-path-audit-latest.json',
        json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
    );
}

foreach ($warnings as $warning) {
    fwrite(STDERR, '[LC-08 warning] ' . $warning . PHP_EOL);
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-08 error] ' . $error . PHP_EOL);
    }

    exit(1);
}

fwrite(STDOUT, 'LC-08 service address/location path audit passed.' . PHP_EOL);
