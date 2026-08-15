<?php

declare(strict_types=1);

/**
 * Locating LC-07 Service-family audit.
 *
 * Report-only scanner for Service/ServiceInterface class-form normalization.
 */

$root = dirname(__DIR__, 2);
$src = $root . '/src';
$reportDir = $root . '/report';

if (!is_dir($src)) {
    fwrite(STDERR, "Locating source directory not found: {$src}\n");
    exit(2);
}

if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    fwrite(STDERR, "Unable to create report directory: {$reportDir}\n");
    exit(2);
}

$targets = [
    'Service' => $src . '/Service',
    'ServiceInterface' => $src . '/ServiceInterface',
];

$rows = [];
$issues = [];
$families = [];

foreach ($targets as $layer => $dir) {
    if (!is_dir($dir)) {
        $issues[] = issue('warning', 'missing_layer_directory', $layer, relativePath($root, $dir), "Expected {$layer} directory is missing.");
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || $file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getPathname();
        $relative = relativePath($root, $path);
        $content = (string) file_get_contents($path);
        $namespace = extractNamespace($content) ?? '';
        $symbol = extractSymbol($content);
        $className = $symbol['nameEntity'] ?? pathinfo($path, PATHINFO_FILENAME);
        $kind = $symbol['kind'] ?? 'unknown';
        $direction = directionName($root, $path, $layer);
        $expectedNamespace = expectedNamespaceForPath($root, $path, $layer);
        $isAppLocationNamespace = $namespace === $expectedNamespace;
        $isSmartresponsorNamespace = str_starts_with($namespace, 'Smartresponsor\\');
        $hasLocationPrefix = str_starts_with($className, 'Location') || str_starts_with($className, 'SmartresponsorLocation');
        $hasAllowedServiceSuffix = preg_match('/(Service|Capability|Factory|Guard|Backend|Decorator|Resolver|Provider|Exporter|Collector|Pipeline|Parser|Normalizer|Validator|Mapper|Router|Registry|Policy|Manager|Scheduler|Executor|Runner|Ranker|Adapter|Bridge|Client|Repository|Store|Recorder|Engine|Calibrator|Limiter|Toggle|Sweeper|Canonicalizer|Sanitizer)$/', $className) === 1;
        $hasInterfaceSuffix = str_ends_with($className, 'Interface');
        $logicalFamily = preg_replace('/Interface$/', '', $className) ?: $className;

        $rows[] = [
            'layer' => $layer,
            'direction' => $direction,
            'path' => $relative,
            'namespace' => $namespace,
            'kind' => $kind,
            'symbol' => $className,
            'logical_family' => $logicalFamily,
            'app_location_namespace' => $isAppLocationNamespace,
            'smartresponsor_namespace' => $isSmartresponsorNamespace,
            'location_prefix' => $hasLocationPrefix,
            'allowed_service_suffix' => $hasAllowedServiceSuffix,
            'interface_suffix' => $hasInterfaceSuffix,
        ];

        $families[$logicalFamily][$layer][] = $relative;

        if ($layer === 'ServiceInterface' && !$hasInterfaceSuffix) {
            $issues[] = issue('error', 'service_interface_missing_suffix', $layer, $relative, "ServiceInterface symbol {$className} must end with Interface.");
        }
        if ($layer === 'Service' && $kind === 'interface') {
            $issues[] = issue('error', 'interface_in_service_layer', $layer, $relative, 'Interface is placed in Service layer.');
        }
        if ($layer === 'ServiceInterface' && $kind !== 'interface') {
            $issues[] = issue('error', 'non_interface_in_service_interface_layer', $layer, $relative, 'Non-interface symbol is placed in ServiceInterface layer.');
        }
        if (!$isAppLocationNamespace) {
            $issues[] = issue($isSmartresponsorNamespace ? 'warning' : 'error', 'service_namespace_path_mismatch', $layer, $relative, "Namespace {$namespace} does not match expected {$expectedNamespace}.");
        }
        if ($layer === 'Service' && !$hasAllowedServiceSuffix) {
            $issues[] = issue('warning', 'service_class_suffix_not_canonical', $layer, $relative, "Service class {$className} has no canonical service-form suffix.");
        }
        if ($layer === 'Service' && !$hasLocationPrefix && !$isSmartresponsorNamespace) {
            $issues[] = issue('warning', 'service_class_missing_location_prefix', $layer, $relative, "App service class {$className} should carry Location prefix where practical.");
        }
    }
}

ksort($families);
$familyRows = [];
foreach ($families as $family => $layers) {
    $servicePaths = $layers['Service'] ?? [];
    $interfacePaths = $layers['ServiceInterface'] ?? [];
    $familyRows[] = [
        'family' => $family,
        'service_count' => count($servicePaths),
        'service_interface_count' => count($interfacePaths),
        'service_paths' => $servicePaths,
        'service_interface_paths' => $interfacePaths,
    ];
    if ($servicePaths !== [] && $interfacePaths === []) {
        $issues[] = issue('warning', 'service_without_mirrored_interface', 'Service', implode(';', $servicePaths), "Service family {$family} has no mirrored ServiceInterface family.");
    }
}

$summary = [
    'generated_at' => gmdate('c'),
    'component' => 'Locating',
    'stage' => 'LC-07 service-family audit',
    'service_files' => count(array_filter($rows, static fn (array $row): bool => $row['layer'] === 'Service')),
    'service_interface_files' => count(array_filter($rows, static fn (array $row): bool => $row['layer'] === 'ServiceInterface')),
    'families' => count($familyRows),
    'errors' => count(array_filter($issues, static fn (array $issue): bool => $issue['severity'] === 'error')),
    'warnings' => count(array_filter($issues, static fn (array $issue): bool => $issue['severity'] === 'warning')),
];

file_put_contents($reportDir . '/locating-service-family-audit-latest.json', json_encode([
    'summary' => $summary,
    'issues' => $issues,
    'families' => $familyRows,
    'rows' => $rows,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n");
writeCsv($reportDir . '/locating-service-family-audit-latest.csv', $rows);
writeShortlist($reportDir . '/locating-service-family-normalization-shortlist.md', $summary, $issues);

printf("Locating LC-07 service-family audit complete: %d services, %d interfaces, %d warning(s), %d error(s).\n", $summary['service_files'], $summary['service_interface_files'], $summary['warnings'], $summary['errors']);
exit(0);

function extractNamespace(string $content): ?string
{
    return preg_match('/^namespace\s+([^;]+);/m', $content, $m) === 1 ? trim($m[1]) : null;
}

function extractSymbol(string $content): array
{
    if (preg_match('/^\s*(?:(?:final|abstract)\s+)?(class|interface|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)/m', $content, $m) === 1) {
        return ['kind' => $m[1], 'nameEntity' => $m[2]];
    }
    return [];
}

function directionName(string $root, string $path, string $layer): string
{
    $relative = relativePath($root, $path);
    $prefix = 'src/' . $layer . '/';
    if (!str_starts_with($relative, $prefix)) {
        return 'Unknown';
    }
    $tail = substr($relative, strlen($prefix));
    $parts = explode('/', $tail);
    return $parts[0] ?? 'Unknown';
}

function expectedNamespaceForPath(string $root, string $path, string $layer): string
{
    $relative = relativePath($root, $path);
    $prefix = 'src/' . $layer . '/';
    $tail = str_starts_with($relative, $prefix) ? substr($relative, strlen($prefix)) : basename($relative);
    $directory = trim(str_replace('/', '\\', dirname($tail)), '.\\');

    return 'App\\Locating\\' . $layer . ($directory !== '' ? '\\' . $directory : '');
}

function issue(string $severity, string $code, string $layer, string $path, string $message): array
{
    return ['severity' => $severity, 'code' => $code, 'layer' => $layer, 'path' => $path, 'message' => $message];
}

function relativePath(string $root, string $path): string
{
    $root = rtrim(str_replace('\\', '/', $root), '/') . '/';
    $path = str_replace('\\', '/', $path);
    return str_starts_with($path, $root) ? substr($path, strlen($root)) : $path;
}

function writeCsv(string $path, array $rows): void
{
    $handle = fopen($path, 'wb');
    if ($handle === false) {
        throw new RuntimeException("Unable to write CSV: {$path}");
    }
    $headers = ['layer', 'direction', 'path', 'namespace', 'kind', 'symbol', 'logical_family', 'app_location_namespace', 'smartresponsor_namespace', 'location_prefix', 'allowed_service_suffix', 'interface_suffix'];
    fputcsv($handle, $headers, ',', '"', '');
    foreach ($rows as $row) {
        fputcsv($handle, array_map(static fn ($value): string => is_bool($value) ? ($value ? 'yes' : 'no') : (string) $value, array_intersect_key($row, array_flip($headers))), ',', '"', '');
    }
    fclose($handle);
}

function writeShortlist(string $path, array $summary, array $issues): void
{
    $lines = [
        '# Locating LC-07 Service-family normalization shortlist',
        '',
        'Generated: ' . $summary['generated_at'],
        '',
        '## Summary',
        '',
        '- Service files: ' . $summary['service_files'],
        '- ServiceInterface files: ' . $summary['service_interface_files'],
        '- Logical families: ' . $summary['families'],
        '- Warnings: ' . $summary['warnings'],
        '- Errors: ' . $summary['errors'],
        '',
        '## Highest-priority findings',
        '',
    ];
    $selected = array_slice($issues, 0, 80);
    if ($selected === []) {
        $lines[] = '- No findings.';
    }
    foreach ($selected as $issue) {
        $lines[] = sprintf('- `%s` `%s` `%s`: %s', $issue['severity'], $issue['code'], $issue['path'], $issue['message']);
    }
    $lines[] = '';
    $lines[] = 'LC-08 should start with App-owned Service/ServiceInterface pairs before touching Smartresponsor legacy clusters.';
    file_put_contents($path, implode("\n", $lines) . "\n");
}
