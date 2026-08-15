<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);

$scopes = [
    'service' => [
        'dir' => 'src/Service/Locator',
        'expected_namespace' => 'App\Locating\\Service\\Locator',
        'target_namespace_prefix' => 'App\Locating\\Service\\Locator\\Location',
        'expected_kind' => 'class',
    ],
    'service_interface' => [
        'dir' => 'src/ServiceInterface/Locator',
        'expected_namespace' => 'App\Locating\\ServiceInterface\\Locator',
        'target_namespace_prefix' => 'App\Locating\\ServiceInterface\\Locator\\Location',
        'expected_kind' => 'interface',
    ],
];

$rows = [];
$warnings = [];
$violations = [];
$summary = [
    'service_files' => 0,
    'service_interface_files' => 0,
    'smartresponsor_namespace_files' => 0,
    'app_namespace_files' => 0,
    'unexpected_namespace_files' => 0,
    'missing_suffix_files' => 0,
    'legacy_interface_references' => 0,
];

$allowedServiceSuffixes = [
    'Adapter',
    'Aggregator',
    'Builder',
    'Capability',
    'Client',
    'Decorator',
    'Factory',
    'Filter',
    'Guard',
    'Handler',
    'Hydrator',
    'Manager',
    'Mapper',
    'Normalizer',
    'Planner',
    'Policy',
    'Provider',
    'Reader',
    'Resolver',
    'Router',
    'Selector',
    'Service',
    'Strategy',
    'Writer',
];

foreach ($scopes as $scope => $config) {
    $directory = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $config['dir']);
    if (!is_dir($directory)) {
        $warnings[] = sprintf('Legacy locator directory is absent: %s', $config['dir']);
        continue;
    }

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS));
    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || $file->getExtension() !== 'php') {
            continue;
        }

        $absolutePath = $file->getPathname();
        $relativePath = str_replace('\\', '/', substr($absolutePath, strlen($root) + 1));
        $code = (string) file_get_contents($absolutePath);
        $declaredName = pathinfo($absolutePath, PATHINFO_FILENAME);
        $namespace = null;
        $kind = null;
        $extends = null;
        $implements = [];

        if (preg_match('/^namespace\s+([^;]+);/m', $code, $m)) {
            $namespace = trim($m[1]);
        }

        if (preg_match('/^\s*(?:final\s+|abstract\s+)?class\s+' . preg_quote($declaredName, '/') . '(?:\s+extends\s+([^\s{]+))?(?:\s+implements\s+([^\{]+))?/m', $code, $m)) {
            $kind = 'class';
            $extends = isset($m[1]) ? trim($m[1]) : null;
            if (isset($m[2])) {
                $implements = array_values(array_filter(array_map(static fn (string $item): string => trim($item), explode(',', trim($m[2])))));
            }
        } elseif (preg_match('/^\s*interface\s+' . preg_quote($declaredName, '/') . '(?:\s+extends\s+([^\{]+))?/m', $code, $m)) {
            $kind = 'interface';
            $extends = isset($m[1]) ? trim($m[1]) : null;
        } elseif (preg_match('/^\s*trait\s+' . preg_quote($declaredName, '/') . '\b/m', $code)) {
            $kind = 'trait';
        }

        if ($scope === 'service') {
            ++$summary['service_files'];
        } else {
            ++$summary['service_interface_files'];
        }

        if ($namespace !== null && str_starts_with($namespace, 'App\Locating\\')) {
            ++$summary['smartresponsor_namespace_files'];
        } elseif ($namespace !== null && str_starts_with($namespace, 'App\Locating\\')) {
            ++$summary['app_namespace_files'];
        } else {
            ++$summary['unexpected_namespace_files'];
        }

        $expectedKind = $config['expected_kind'];
        if ($kind !== $expectedKind) {
            $warnings[] = sprintf('Unexpected declaration kind for %s: expected %s, got %s', $relativePath, $expectedKind, $kind ?? '[missing]');
        }

        $hasExpectedLegacyNamespace = $namespace === $config['expected_namespace'];
        if (!$hasExpectedLegacyNamespace) {
            $warnings[] = sprintf('Legacy locator namespace drift in %s: expected %s, got %s', $relativePath, $config['expected_namespace'], $namespace ?? '[missing]');
        }

        $hasFormSuffix = false;
        if ($scope === 'service_interface') {
            $hasFormSuffix = str_ends_with($declaredName, 'Interface');
        } else {
            foreach ($allowedServiceSuffixes as $suffix) {
                if (str_ends_with($declaredName, $suffix)) {
                    $hasFormSuffix = true;
                    break;
                }
            }
        }

        if (!$hasFormSuffix) {
            ++$summary['missing_suffix_files'];
        }

        $legacyInterfaceRefs = [];
        if ($scope === 'service') {
            foreach ($implements as $implemented) {
                if (str_contains($implemented, 'LegacyInterface')) {
                    $legacyInterfaceRefs[] = $implemented;
                    ++$summary['legacy_interface_references'];
                }
            }
        }

        $suggestedTargetPath = null;
        if ($scope === 'service') {
            $suggestedTargetPath = 'src/Service/Locator/Location/' . $declaredName . '.php';
        } else {
            $suggestedTargetPath = 'src/ServiceInterface/Locator/Location/' . $declaredName . '.php';
        }

        $rows[] = [
            'scope' => $scope,
            'path' => $relativePath,
            'declared_name' => $declaredName,
            'kind' => $kind,
            'namespace' => $namespace,
            'expected_legacy_namespace' => $config['expected_namespace'],
            'target_namespace_prefix' => $config['target_namespace_prefix'],
            'has_form_suffix' => $hasFormSuffix,
            'extends' => $extends,
            'implements' => implode('|', $implements),
            'legacy_interface_refs' => implode('|', $legacyInterfaceRefs),
            'suggested_target_path' => $suggestedTargetPath,
        ];
    }
}

usort($rows, static fn (array $a, array $b): int => [$a['scope'], $a['path']] <=> [$b['scope'], $b['path']]);

$shortlistRows = array_values(array_filter(
    $rows,
    static fn (array $row): bool => !$row['has_form_suffix'] || str_contains((string) $row['legacy_interface_refs'], 'LegacyInterface')
));

$reportDir = $root . DIRECTORY_SEPARATOR . 'report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}

file_put_contents(
    $reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-legacy-audit-latest.json',
    json_encode([
        'component' => 'Locating',
        'wave' => 'LC-14',
        'scope' => 'Service/Locator legacy retirement audit',
        'summary' => $summary,
        'violations' => $violations,
        'warnings' => $warnings,
        'rows' => $rows,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
);

$csv = fopen($reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-legacy-audit-latest.csv', 'wb');
fputcsv($csv, ['scope', 'path', 'declared_name', 'kind', 'namespace', 'expected_legacy_namespace', 'target_namespace_prefix', 'has_form_suffix', 'extends', 'implements', 'legacy_interface_refs', 'suggested_target_path']);
foreach ($rows as $row) {
    fputcsv($csv, [
        $row['scope'],
        $row['path'],
        $row['declared_name'],
        $row['kind'] ?? '',
        $row['namespace'] ?? '',
        $row['expected_legacy_namespace'],
        $row['target_namespace_prefix'],
        $row['has_form_suffix'] ? 'yes' : 'no',
        $row['extends'] ?? '',
        $row['implements'],
        $row['legacy_interface_refs'],
        $row['suggested_target_path'],
    ]);
}
fclose($csv);

$shortlist = [];
$shortlist[] = '# Locating LC-14 service locator legacy shortlist';
$shortlist[] = '';
$shortlist[] = 'This generated shortlist identifies legacy `App\Locating\\...Locator` service files that should be handled before a physical path move or namespace transition.';
$shortlist[] = '';
$shortlist[] = '| Scope | File | Reason | Suggested target path |';
$shortlist[] = '| --- | --- | --- | --- |';
foreach ($shortlistRows as $row) {
    $reasons = [];
    if (!$row['has_form_suffix']) {
        $reasons[] = 'missing canonical service/form suffix';
    }
    if ($row['legacy_interface_refs'] !== '') {
        $reasons[] = 'implements LegacyInterface contract';
    }
    $shortlist[] = sprintf(
        '| `%s` | `%s` | %s | `%s` |',
        $row['scope'],
        $row['path'],
        implode('; ', $reasons),
        $row['suggested_target_path']
    );
}
if ($shortlistRows === []) {
    $shortlist[] = '| - | - | No immediate legacy blockers found. | - |';
}
$shortlist[] = '';
file_put_contents($reportDir . DIRECTORY_SEPARATOR . 'locating-service-locator-legacy-shortlist.md', implode(PHP_EOL, $shortlist));

if ($violations !== []) {
    fwrite(STDERR, "Locating LC-14 service locator legacy canon failed:\n");
    foreach ($violations as $violation) {
        fwrite(STDERR, ' - ' . $violation . "\n");
    }
    exit(1);
}

echo sprintf(
    "Locating LC-14 service locator legacy audit completed: %d service files, %d service-interface files, %d Smartresponsor namespace files, %d missing suffix candidates.\n",
    $summary['service_files'],
    $summary['service_interface_files'],
    $summary['smartresponsor_namespace_files'],
    $summary['missing_suffix_files']
);

if ($warnings !== []) {
    echo sprintf("Warnings recorded: %d. See report/locating-service-locator-legacy-audit-latest.json.\n", count($warnings));
}
