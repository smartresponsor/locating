<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$ruleSet = $root . DIRECTORY_SEPARATOR . '.semgrep' . DIRECTORY_SEPARATOR . 'location-security.yml';
$scanPaths = [
    $root . DIRECTORY_SEPARATOR . 'src',
    $root . DIRECTORY_SEPARATOR . 'config',
    $root . DIRECTORY_SEPARATOR . 'public',
];

$candidatePattern = '~\\b(?:eval|unserialize|exec|system|shell_exec|passthru|putenv)\\s*\\(|\\$_ENV\\s*\\[~';
$candidates = [];

foreach ($scanPaths as $scanPath) {
    if (!is_dir($scanPath)) {
        continue;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($scanPath, FilesystemIterator::SKIP_DOTS),
    );

    foreach ($iterator as $file) {
        if (!$file->isFile() || strtolower($file->getExtension()) !== 'php') {
            continue;
        }

        $contents = file_get_contents($file->getPathname());
        if ($contents !== false && preg_match($candidatePattern, $contents) === 1) {
            $candidates[] = $file->getPathname();
        }
    }
}

sort($candidates);

if ($candidates === []) {
    fwrite(STDOUT, "[security] semgrep prefilter found no risky PHP primitive candidates.\n");
    exit(0);
}

$command = 'semgrep scan --config ' . escapeshellarg($ruleSet) . ' --error --strict --metrics=off --disable-version-check --timeout 30 --timeout-threshold 1 --jobs 2';

foreach ($candidates as $path) {
    $command .= ' ' . escapeshellarg($path);
}

fwrite(STDOUT, sprintf("[security] semgrep scanning %d candidate file(s).\n", count($candidates)));
passthru($command, $exitCode);
exit($exitCode);
