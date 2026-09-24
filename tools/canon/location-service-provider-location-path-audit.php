<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

$expectedMoves = [
    'src/Service/Provider/AddressReverseResultNormalizer.php' => 'src/Service/Provider/Location/AddressReverseResultNormalizer.php',
    'src/Service/Provider/AddressSuggestionRanker.php' => 'src/Service/Provider/Location/AddressSuggestionRanker.php',
    'src/Service/Provider/OrderedAddressReverseProvider.php' => 'src/Provider/Location/OrderedAddressReverseProvider.php',
    'src/Service/Provider/OrderedAddressSuggestionProvider.php' => 'src/Provider/Location/OrderedAddressSuggestionProvider.php',
    'src/Service/Provider/PolicyAddressReverseSourceOrder.php' => 'src/Service/Provider/Location/PolicyAddressReverseSourceOrder.php',
    'src/Service/Provider/PolicyAddressSuggestionSourceOrder.php' => 'src/Service/Provider/Location/PolicyAddressSuggestionSourceOrder.php',
    'src/Service/Provider/StaticAddressReverseSourceOrder.php' => 'src/Service/Provider/Location/StaticAddressReverseSourceOrder.php',
    'src/Service/Provider/StaticAddressSuggestionSourceOrder.php' => 'src/Service/Provider/Location/StaticAddressSuggestionSourceOrder.php',
    'src/ServiceInterface/Provider/AddressReverseProviderInterface.php' => 'src/ProviderInterface/Location/AddressReverseProviderInterface.php',
    'src/ServiceInterface/Provider/AddressReverseResultNormalizerInterface.php' => 'src/ServiceInterface/Provider/Location/AddressReverseResultNormalizerInterface.php',
    'src/ServiceInterface/Provider/AddressReverseSourceCostPolicyInterface.php' => 'src/ServiceInterface/Provider/Location/AddressReverseSourceCostPolicyInterface.php',
    'src/ServiceInterface/Provider/AddressReverseSourceHealthPolicyInterface.php' => 'src/ServiceInterface/Provider/Location/AddressReverseSourceHealthPolicyInterface.php',
    'src/ServiceInterface/Provider/AddressReverseSourceInterface.php' => 'src/ServiceInterface/Provider/Location/AddressReverseSourceInterface.php',
    'src/ServiceInterface/Provider/AddressReverseSourceOrderInterface.php' => 'src/ServiceInterface/Provider/Location/AddressReverseSourceOrderInterface.php',
    'src/ServiceInterface/Provider/AddressReverseSourceQuotaPolicyInterface.php' => 'src/ServiceInterface/Provider/Location/AddressReverseSourceQuotaPolicyInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionProviderInterface.php' => 'src/ProviderInterface/Location/AddressSuggestionProviderInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionRankerInterface.php' => 'src/ServiceInterface/Provider/Location/AddressSuggestionRankerInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionSourceCostPolicyInterface.php' => 'src/ServiceInterface/Provider/Location/AddressSuggestionSourceCostPolicyInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionSourceHealthPolicyInterface.php' => 'src/ServiceInterface/Provider/Location/AddressSuggestionSourceHealthPolicyInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionSourceInterface.php' => 'src/ServiceInterface/Provider/Location/AddressSuggestionSourceInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionSourceOrderInterface.php' => 'src/ServiceInterface/Provider/Location/AddressSuggestionSourceOrderInterface.php',
    'src/ServiceInterface/Provider/AddressSuggestionSourceQuotaPolicyInterface.php' => 'src/ServiceInterface/Provider/Location/AddressSuggestionSourceQuotaPolicyInterface.php',
    'src/ServiceInterface/Provider/ProviderCostSignalReaderInterface.php' => 'src/ServiceInterface/Provider/Location/ProviderCostSignalReaderInterface.php',
    'src/ServiceInterface/Provider/ProviderHealthSignalReaderInterface.php' => 'src/ServiceInterface/Provider/Location/ProviderHealthSignalReaderInterface.php',
    'src/ServiceInterface/Provider/ProviderQuotaSignalReaderInterface.php' => 'src/ServiceInterface/Provider/Location/ProviderQuotaSignalReaderInterface.php',
];

$violations = [];
$warnings = [];
$rows = [];

foreach ($expectedMoves as $old => $new) {
    $oldPath = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $old);
    $newPath = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $new);

    $oldExists = is_file($oldPath);
    $newExists = is_file($newPath);

    if ($oldExists) {
        $violations[] = 'Legacy provider service path still exists: ' . $old;
    }

    if (!$newExists) {
        $violations[] = 'Expected provider location path is missing: ' . $new;
    }

    $namespace = null;
    $kind = null;
    $nameEntity = basename($new, '.php');
    if ($newExists) {
        $code = (string) file_get_contents($newPath);
        if (preg_match('/^namespace\s+([^;]+);/m', $code, $m)) {
            $namespace = $m[1];
        }
        if (preg_match('/^\s*(?:final\s+|abstract\s+)?class\s+' . preg_quote($nameEntity, '/') . '\b/m', $code)) {
            $kind = 'class';
        } elseif (preg_match('/^\s*interface\s+' . preg_quote($nameEntity, '/') . '\b/m', $code)) {
            $kind = 'interface';
        }
    }

    $expectedNamespace = match (true) {
        str_starts_with($new, 'src/ProviderInterface/') => 'App\Locating\\ProviderInterface\\Location',
        str_starts_with($new, 'src/Provider/') => 'App\Locating\\Provider\\Location',
        str_starts_with($new, 'src/ServiceInterface/') => 'App\Locating\\ServiceInterface\\Provider\\Location',
        default => 'App\Locating\\Service\\Provider\\Location',
    };

    if ($newExists && $namespace !== $expectedNamespace) {
        $violations[] = sprintf('Unexpected namespace for %s: expected %s, got %s', $new, $expectedNamespace, $namespace ?? '[missing]');
    }

    if ((str_starts_with($new, 'src/ServiceInterface/') || str_starts_with($new, 'src/ProviderInterface/')) && $kind !== 'interface') {
        $violations[] = sprintf('Typed provider interface file must declare an interface: %s', $new);
    }

    if (str_starts_with($new, 'src/Service/') && $kind !== 'class') {
        $violations[] = sprintf('Service provider file must declare a class: %s', $new);
    }

    $rows[] = [
        'old_path' => $old,
        'new_path' => $new,
        'old_exists' => $oldExists,
        'new_exists' => $newExists,
        'namespace' => $namespace,
        'kind' => $kind,
    ];
}

$providerRoot = $root . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . 'Provider';
if (is_dir($providerRoot)) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($providerRoot, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $fileInfo) {
        if (!$fileInfo instanceof SplFileInfo || !$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
            continue;
        }
        if (str_starts_with($fileInfo->getBasename('.php'), 'Legacy')) {
            $relativeLegacyPath = str_replace('\\', '/', substr($fileInfo->getPathname(), strlen($root) + 1));
            $violations[] = 'Retired Legacy provider service still exists: ' . $relativeLegacyPath;
        }
    }
}

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-provider-location-path-audit-latest.json',
    json_encode([
        'component' => 'Locating',
        'wave' => 'LC-12',
        'scope' => 'Service/Provider/Location path normalization',
        'violations' => $violations,
        'warnings' => $warnings,
        'rows' => $rows,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$csv = fopen($reportDir . DIRECTORY_SEPARATOR . 'locating-service-provider-location-path-audit-latest.csv', 'wb');
fputcsv($csv, ['old_path', 'new_path', 'old_exists', 'new_exists', 'namespace', 'kind'], ',', '"', '');
foreach ($rows as $row) {
    fputcsv($csv, [
        $row['old_path'],
        $row['new_path'],
        $row['old_exists'] ? 'yes' : 'no',
        $row['new_exists'] ? 'yes' : 'no',
        $row['namespace'] ?? '',
        $row['kind'] ?? '',
    ], ',', '"', '');
}
fclose($csv);

if ($violations !== []) {
    fwrite(STDERR, "Locating LC-12 provider service path canon failed:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, ' - ' . $violation . "\n");
    }
    exit(1);
}

echo "Locating LC-12 provider service path canon passed.\n";
