<?php

declare(strict_types=1);

/**
 * Locating structure audit gate.
 *
 * This script is intentionally non-destructive. It scans the current repository
 * and reports canonization blockers for the Locating/Location Symfony-oriented
 * cleanup milestone.
 */

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$requiredFiles = [
    'composer.json',
    'src',
    'deploy/nginx/nginx.conf',
];

foreach ($requiredFiles as $path) {
    if (!file_exists($root . DIRECTORY_SEPARATOR . $path)) {
        $errors[] = "Missing required path: {$path}";
    }
}

if (file_exists($root . '/config/nginx.conf')) {
    $errors[] = 'Legacy deploy file still present: config/nginx.conf; use deploy/nginx/nginx.conf.';
}

$composerPath = $root . '/composer.json';
if (is_file($composerPath)) {
    $composer = json_decode((string) file_get_contents($composerPath), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        $autoload = $composer['autoload']['psr-4'] ?? [];
        if (!array_key_exists('App\Locating\\', $autoload)) {
            $errors[] = 'composer.json must expose App\Locating\\ as the production Symfony namespace.';
        }
        if (array_key_exists('Smartresponsor\\', $autoload)) {
            $errors[] = 'composer.json must not expose the retired Smartresponsor\\ production namespace.';
        }
    }
}

$src = $root . '/src';
$smartresponsorFiles = [];
$genericNames = [];
$forbiddenLayerDirs = [];

if (is_dir($src)) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (!$file instanceof SplFileInfo || $file->getExtension() !== 'php') {
            continue;
        }

        $path = $file->getPathname();
        $relative = str_replace($root . DIRECTORY_SEPARATOR, '', $path);
        $code = (string) file_get_contents($path);

        if (preg_match('/^namespace\s+Smartresponsor\\\\/m', $code) === 1) {
            $smartresponsorFiles[] = $relative;
        }

        if (preg_match('/^\s*(?:final\s+|abstract\s+)?(?:class|interface|trait|enum)\s+([A-Za-z_][A-Za-z0-9_]*)/m', $code, $m) === 1) {
            $className = $m[1];
            $hasBusinessSubject = preg_match('/(Location|Locator|Geo|Address|Coordinate|Provider|Region|Postal|Batch|Tenant|Governance|Policy|Suggest|Reverse)/', $className) === 1;
            $hasTechnicalForm = preg_match('/(Entity|Interface|Service|Service|Command|Message|Handler|Provider|Repository|Subscriber|Listener|Factory|Registry|Policy|Metric|Result|Model|Value|Exception|Bundle)$/', $className) === 1;

            if (!$hasBusinessSubject || !$hasTechnicalForm) {
                $genericNames[] = $relative . ' :: ' . $className;
            }
        }
    }

    foreach (['Domain', 'Security'] as $forbidden) {
        $candidate = $src . '/' . $forbidden;
        if (is_dir($candidate)) {
            $forbiddenLayerDirs[] = 'src/' . $forbidden;
        }
    }
}

if ($forbiddenLayerDirs !== []) {
    foreach ($forbiddenLayerDirs as $dir) {
        $errors[] = "Forbidden mixed-purpose source layer: {$dir}";
    }
}

if ($smartresponsorFiles !== []) {
    $warnings[] = 'Smartresponsor namespace files remaining: ' . count($smartresponsorFiles);
}

if ($genericNames !== []) {
    $warnings[] = 'Potentially non-canonical class/interface names: ' . count($genericNames);
}

$report = [
    'component' => 'Locating',
    'entity' => 'Location',
    'errors' => $errors,
    'warnings' => $warnings,
    'samples' => [
        'smartresponsor_namespace_files' => array_slice($smartresponsorFiles, 0, 25),
        'generic_or_weak_class_names' => array_slice($genericNames, 0, 50),
    ],
];

$reportDir = $root . '/report';
if (is_dir($reportDir) || mkdir($reportDir, 0775, true)) {
    file_put_contents(
        $reportDir . '/locating-structure-audit-latest.json',
        json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL
    );
}

foreach ($errors as $error) {
    fwrite(STDERR, "[ERROR] {$error}" . PHP_EOL);
}
foreach ($warnings as $warning) {
    fwrite(STDOUT, "[WARN] {$warning}" . PHP_EOL);
}

if ($errors !== []) {
    fwrite(STDERR, 'Locating structure audit failed.' . PHP_EOL);
    exit(1);
}

fwrite(STDOUT, 'Locating structure audit completed with ' . count($warnings) . ' warning(s).' . PHP_EOL);
exit(0);
