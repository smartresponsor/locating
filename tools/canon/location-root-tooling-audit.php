<?php

declare(strict_types=1);

/**
 * Locating root tooling audit.
 *
 * This gate is intentionally non-destructive. It verifies that Locating uses a
 * single canonical PHP-CS-Fixer config and keeps deploy/runtime tooling out of
 * the application root whenever a canonical deploy/ path is available.
 */

$root = dirname(__DIR__, 2);
$errors = [];
$warnings = [];

$canonicalCsFixer = '.php-cs-fixer.dist.php';
$legacyCsFixerFiles = [
    '.php-cs-fixer.php',
    'php-cs-fixer.dist.php',
];

if (!is_file($root . DIRECTORY_SEPARATOR . $canonicalCsFixer)) {
    $errors[] = "Missing canonical PHP-CS-Fixer config: {$canonicalCsFixer}";
}

foreach ($legacyCsFixerFiles as $legacyFile) {
    if (is_file($root . DIRECTORY_SEPARATOR . $legacyFile)) {
        $errors[] = "Legacy duplicate PHP-CS-Fixer config still present: {$legacyFile}";
    }
}

if (is_file($root . '/config/nginx.conf')) {
    $errors[] = 'Legacy nginx config still present at config/nginx.conf; expected deploy/nginx/nginx.conf.';
}

if (!is_file($root . '/deploy/nginx/nginx.conf')) {
    $warnings[] = 'deploy/nginx/nginx.conf not found. Apply LC-01 before enforcing deploy root cleanup.';
}

$composerPath = $root . '/composer.json';
if (is_file($composerPath)) {
    $composer = json_decode((string) file_get_contents($composerPath), true);
    if (!is_array($composer)) {
        $errors[] = 'composer.json is not valid JSON.';
    } else {
        $scripts = $composer['scripts'] ?? [];
        foreach (['cs:check', 'cs:fix', 'canon:root-tooling'] as $scriptName) {
            if (!array_key_exists($scriptName, $scripts)) {
                $warnings[] = "composer.json does not expose script: {$scriptName}";
            }
        }
    }
}

$report = [
    'component' => 'Locating',
    'wave' => 'LC-02',
    'scope' => 'root-tooling-cleanup',
    'canonical_php_cs_fixer_config' => $canonicalCsFixer,
    'retired_php_cs_fixer_configs' => $legacyCsFixerFiles,
    'errors' => $errors,
    'warnings' => $warnings,
];

$reportDir = $root . '/report';
if (is_dir($reportDir) || mkdir($reportDir, 0775, true)) {
    file_put_contents(
        $reportDir . '/locating-root-tooling-audit-latest.json',
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
    fwrite(STDERR, 'Locating root tooling audit failed.' . PHP_EOL);
    exit(1);
}

fwrite(STDOUT, 'Locating root tooling audit completed with ' . count($warnings) . ' warning(s).' . PHP_EOL);
exit(0);
