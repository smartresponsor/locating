<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$console = $root . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'console';
$importmapCandidates = [
    $root . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'importmap.php',
    $root . DIRECTORY_SEPARATOR . 'importmap.php',
    $root . DIRECTORY_SEPARATOR . 'importmap.json',
];

$hasImportmap = false;
foreach ($importmapCandidates as $candidate) {
    if (is_file($candidate)) {
        $hasImportmap = true;
        break;
    }
}

if (!$hasImportmap) {
    fwrite(STDOUT, "[security] importmap audit skipped: importmap is not configured in this repository.\n");
    exit(0);
}

if (!is_file($console)) {
    fwrite(STDERR, "[security] importmap is configured, but bin/console is missing. Cannot run importmap:audit.\n");
    exit(2);
}

$command = escapeshellarg(PHP_BINARY) . ' ' . escapeshellarg($console) . ' importmap:audit --no-interaction';
passthru($command, $exitCode);
exit($exitCode);
