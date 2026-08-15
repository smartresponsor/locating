<?php

declare(strict_types=1);

/**
 * Locating source inventory and class-form classifier.
 *
 * Non-destructive scanner. It writes JSON/CSV reports used to plan exact
 * touched-file migration waves from repository facts instead of assumptions.
 */

$root = dirname(__DIR__, 2);
$src = $root . '/src';
$reportDir = $root . '/report';
$normalizedRoot = rtrim(str_replace('\\', '/', $root), '/') . '/';

if (!is_dir($src)) {
    fwrite(STDERR, '[ERROR] Missing src/ directory.' . PHP_EOL);
    exit(1);
}

if (!is_dir($reportDir) && !mkdir($reportDir, 0775, true) && !is_dir($reportDir)) {
    fwrite(STDERR, '[ERROR] Cannot create report/ directory.' . PHP_EOL);
    exit(1);
}

$businessPattern = '/(Location|Locator|Geo|Address|Coordinate|Region|Postal|Provider|Tenant|Governance|Policy|Suggest|Reverse|Batch|Quota|Metric|Audit|Recommendation|Remediation|Execution|Acknowledgement|Explanation|Signal|Trace|Cache|RateLimit|Failover|Health|Cost|Sla|Dsn|Secret)/';
$formPattern = '/(Entity|Interface|Service|Service|Command|Message|Handler|Repository|Subscriber|Listener|Factory|Registry|Policy|Metric|Metrics|Result|Model|Value|ValueObject|Exception|Bundle|Extension|CompilerPass|Middleware|Provider|Adapter|Decorator|Formatter|Router|Resolver|Client|Gateway|Exporter|Importer|Projection|ReadModel|Dto|Request|Response|Fixture|Builder|Mapper|Hydrator|Normalizer|Validator|Guard)$/';

$rows = [];
$summary = [
    'total_php_files' => 0,
    'by_root_namespace' => [],
    'by_top_layer' => [],
    'weak_business_subject' => 0,
    'weak_form_suffix' => 0,
    'path_namespace_mismatch' => 0,
    'smartresponsor_namespace' => 0,
    'plain_app_namespace' => 0,
];

$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS)
);

foreach ($iterator as $file) {
    if (!$file instanceof SplFileInfo || $file->getExtension() !== 'php') {
        continue;
    }

    $absolute = $file->getPathname();
    $normalizedAbsolute = str_replace('\\', '/', $absolute);
    if (!str_starts_with($normalizedAbsolute, $normalizedRoot)) {
        continue;
    }
    $normalizedRelative = substr($normalizedAbsolute, strlen($normalizedRoot));
    $pathParts = explode('/', $normalizedRelative);
    $topLayer = $pathParts[1] ?? '';
    $code = (string) file_get_contents($absolute);

    $namespace = '';
    if (preg_match('/^namespace\s+([^;]+);/m', $code, $m) === 1) {
        $namespace = trim($m[1]);
    }

    $symbolType = '';
    $symbolName = '';
    if (preg_match('/^\s*(?:final\s+|abstract\s+|readonly\s+)*\s*(class|interface|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)/m', $code, $m) === 1) {
        $symbolType = $m[1];
        $symbolName = $m[2];
    }

    $rootNamespace = $namespace === '' ? '(missing)' : explode('\\', $namespace)[0];
    $pathNamespace = trim(substr($normalizedRelative, 4, -4), '/');
    $pathNamespace = str_replace('/', '\\', preg_replace('/\/[^\/]+$/', '', $pathNamespace) ?? '');
    $expectedNamespace = 'App\\Locating' . ($pathNamespace !== '' ? '\\' . $pathNamespace : '');

    $hasBusinessSubject = $symbolName !== '' && preg_match($businessPattern, $symbolName) === 1;
    $hasFormSuffix = $symbolName !== '' && preg_match($formPattern, $symbolName) === 1;
    $pathNamespaceMismatch = $namespace !== '' && $expectedNamespace !== $namespace;

    $summary['total_php_files']++;
    $summary['by_root_namespace'][$rootNamespace] = ($summary['by_root_namespace'][$rootNamespace] ?? 0) + 1;
    $summary['by_top_layer'][$topLayer] = ($summary['by_top_layer'][$topLayer] ?? 0) + 1;

    if (!$hasBusinessSubject) {
        $summary['weak_business_subject']++;
    }
    if (!$hasFormSuffix) {
        $summary['weak_form_suffix']++;
    }
    if ($pathNamespaceMismatch) {
        $summary['path_namespace_mismatch']++;
    }
    if (str_starts_with($namespace, 'Smartresponsor\\')) {
        $summary['smartresponsor_namespace']++;
    }
    if (str_starts_with($namespace, 'App\Locating\\')) {
        $summary['plain_app_namespace']++;
    }

    $rows[] = [
        'path' => $normalizedRelative,
        'top_layer' => $topLayer,
        'namespace' => $namespace,
        'symbol_type' => $symbolType,
        'symbol_name' => $symbolName,
        'root_namespace' => $rootNamespace,
        'has_business_subject' => $hasBusinessSubject ? 'yes' : 'no',
        'has_form_suffix' => $hasFormSuffix ? 'yes' : 'no',
        'path_namespace_mismatch' => $pathNamespaceMismatch ? 'yes' : 'no',
        'migration_bucket' => migrationBucket($topLayer, $namespace, $symbolName, $hasBusinessSubject, $hasFormSuffix),
    ];
}

ksort($summary['by_root_namespace']);
ksort($summary['by_top_layer']);
usort($rows, static fn (array $a, array $b): int => $a['path'] <=> $b['path']);

$report = [
    'component' => 'Locating',
    'entity' => 'Location',
    'wave' => 'LC-03',
    'scope' => 'source inventory and class-form classifier',
    'summary' => $summary,
    'rows' => $rows,
];

file_put_contents(
    $reportDir . '/locating-source-inventory-latest.json',
    json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$csv = fopen($reportDir . '/locating-source-inventory-latest.csv', 'wb');
if ($csv === false) {
    fwrite(STDERR, '[ERROR] Cannot write report/locating-source-inventory-latest.csv' . PHP_EOL);
    exit(1);
}

$headers = ['path', 'top_layer', 'namespace', 'symbol_type', 'symbol_name', 'root_namespace', 'has_business_subject', 'has_form_suffix', 'path_namespace_mismatch', 'migration_bucket'];
fputcsv($csv, $headers, ',', '"', '');
foreach ($rows as $row) {
    fputcsv($csv, $row, ',', '"', '');
}
fclose($csv);

fwrite(STDOUT, 'Locating source inventory completed.' . PHP_EOL);
fwrite(STDOUT, 'PHP files: ' . $summary['total_php_files'] . PHP_EOL);
fwrite(STDOUT, 'Smartresponsor namespace files: ' . $summary['smartresponsor_namespace'] . PHP_EOL);
fwrite(STDOUT, 'Path/namespace mismatches: ' . $summary['path_namespace_mismatch'] . PHP_EOL);
fwrite(STDOUT, 'Weak business subject names: ' . $summary['weak_business_subject'] . PHP_EOL);
fwrite(STDOUT, 'Weak form suffix names: ' . $summary['weak_form_suffix'] . PHP_EOL);
exit(0);

function migrationBucket(string $topLayer, string $namespace, string $symbolName, bool $hasBusinessSubject, bool $hasFormSuffix): string
{
    if ($topLayer === 'Entity' || $topLayer === 'EntityInterface') {
        return 'entity_first';
    }

    if (str_contains($topLayer, 'Interface')) {
        return 'interface_mirror';
    }

    if ($topLayer === 'Bridge') {
        return 'legacy_bridge';
    }

    if (str_starts_with($namespace, 'Smartresponsor\\')) {
        return 'namespace_retirement';
    }

    if (!$hasBusinessSubject || !$hasFormSuffix) {
        return 'class_form_rename';
    }

    if (in_array($topLayer, ['Service', 'Command', 'Message', 'MessageHandler'], true)) {
        return 'runtime_surface';
    }

    if (in_array($topLayer, ['Infrastructure', 'Integration'], true)) {
        return 'infrastructure_surface';
    }

    if ($topLayer === 'Service') {
        return 'service_surface';
    }

    return 'other';
}
