<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$command = 'gitleaks detect --source=' . escapeshellarg($root) . ' --no-banner --redact';

exec('gitleaks version 2>&1', $output, $versionExitCode);
if ($versionExitCode !== 0) {
    fwrite(STDERR, "[security] gitleaks is not available in PATH. Install gitleaks to run the local security pipeline.\n");
    exit(2);
}

passthru($command, $exitCode);
exit($exitCode);
