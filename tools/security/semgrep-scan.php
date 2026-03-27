<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$ruleSet = $root . DIRECTORY_SEPARATOR . '.semgrep' . DIRECTORY_SEPARATOR . 'location-security.yml';
$scanPaths = [
    $root . DIRECTORY_SEPARATOR . 'src',
    $root . DIRECTORY_SEPARATOR . 'config',
    $root . DIRECTORY_SEPARATOR . 'public',
];

exec('semgrep --version 2>&1', $output, $versionExitCode);
if ($versionExitCode !== 0) {
    fwrite(STDERR, "[security] semgrep is not available in PATH. Install Semgrep Community Edition to run the local security pipeline.\n");
    exit(2);
}

$existingScanPaths = array_values(array_filter($scanPaths, static fn (string $path): bool => is_dir($path)));
$command = 'semgrep scan --config ' . escapeshellarg($ruleSet) . ' --error --metrics=off';

foreach ($existingScanPaths as $path) {
    $command .= ' ' . escapeshellarg($path);
}

passthru($command, $exitCode);
exit($exitCode);
