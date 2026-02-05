#!/usr/bin/env php
<?php

declare(strict_types=1);

const CANONICAL_COPYRIGHT = '# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp';
const DEFAULT_TARGETS = [
    'src/Command',
    'src/Bundle',
];

main($argv);

function main(array $argv): void
{
    $options = parseArgs($argv);
    $targets = $options['targets'] !== [] ? $options['targets'] : DEFAULT_TARGETS;

    $phpFiles = collectPhpFiles($targets);
    $violations = [];
    $changedFiles = [];

    foreach ($phpFiles as $file) {
        $content = file_get_contents($file);
        if ($content === false) {
            fwrite(STDERR, "[warn] Cannot read file: {$file}\n");
            continue;
        }

        $analysis = analyzeFile($content);
        if ($analysis['hasBlockCopyright']) {
            $violations[] = $file;
        }

        if ($options['mode'] !== 'apply') {
            continue;
        }

        if (! $analysis['needsRewrite']) {
            continue;
        }

        $rewritten = rewriteFile($content);
        if ($rewritten === $content) {
            continue;
        }

        backupFile($file, $content, $options['backupDir']);
        file_put_contents($file, $rewritten);
        $changedFiles[] = $file;
    }

    if ($options['mode'] === 'check') {
        if ($violations !== []) {
            fwrite(STDERR, "Found block-style copyright headers in: \n");
            foreach ($violations as $file) {
                fwrite(STDERR, " - {$file}\n");
            }
            exit(1);
        }

        fwrite(STDOUT, "OK: no block-style copyright headers found.\n");
        exit(0);
    }

    $remainingViolations = [];
    foreach ($phpFiles as $file) {
        $content = file_get_contents($file);
        if ($content === false) {
            continue;
        }

        if (analyzeFile($content)['hasBlockCopyright']) {
            $remainingViolations[] = $file;
        }
    }

    if ($changedFiles !== []) {
        fwrite(STDOUT, "Changed files (" . count($changedFiles) . "):\n");
        foreach ($changedFiles as $file) {
            fwrite(STDOUT, " - {$file}\n");
        }
        fwrite(STDOUT, "Backups saved to: {$options['backupDir']}\n");
    } else {
        fwrite(STDOUT, "No files changed.\n");
    }

    if ($remainingViolations !== []) {
        fwrite(STDOUT, "Remaining block-style copyright headers after apply: " . count($remainingViolations) . "\n");
        foreach ($remainingViolations as $file) {
            fwrite(STDOUT, " - {$file}\n");
        }
        exit(1);
    }
}

/** @return array{mode:string,targets:array<int,string>,backupDir:string} */
function parseArgs(array $argv): array
{
    $mode = null;
    $targets = [];
    $backupDir = 'var/copyright-normalize-backup';

    foreach (array_slice($argv, 1) as $arg) {
        if ($arg === '--check') {
            $mode = 'check';
            continue;
        }

        if ($arg === '--apply') {
            $mode = 'apply';
            continue;
        }

        if (str_starts_with($arg, '--path=')) {
            $targets[] = trim(substr($arg, strlen('--path=')));
            continue;
        }

        if (str_starts_with($arg, '--backup-dir=')) {
            $backupDir = trim(substr($arg, strlen('--backup-dir=')));
            continue;
        }

        fwrite(STDERR, "Unknown argument: {$arg}\n");
        usageAndExit(2);
    }

    if ($mode === null) {
        usageAndExit(2);
    }

    return ['mode' => $mode, 'targets' => $targets, 'backupDir' => $backupDir];
}

function usageAndExit(int $code): void
{
    fwrite(STDERR, "Usage:\n");
    fwrite(STDERR, "  php tools/copyright-normalize.php --check [--path=src/Command]\n");
    fwrite(STDERR, "  php tools/copyright-normalize.php --apply [--path=src/Command] [--backup-dir=var/copyright-normalize-backup]\n");
    exit($code);
}

/** @return array<int,string> */
function collectPhpFiles(array $targets): array
{
    $files = [];

    foreach ($targets as $target) {
        if (is_file($target) && str_ends_with($target, '.php')) {
            $files[] = $target;
            continue;
        }

        if (! is_dir($target)) {
            fwrite(STDERR, "[warn] Path not found: {$target}\n");
            continue;
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($target, FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator as $entry) {
            if ($entry instanceof SplFileInfo && $entry->isFile() && str_ends_with($entry->getFilename(), '.php')) {
                $files[] = str_replace('\\', '/', $entry->getPathname());
            }
        }
    }

    sort($files);

    return $files;
}

/** @return array{hasBlockCopyright:bool,needsRewrite:bool} */
function analyzeFile(string $content): array
{
    $hasBlockCopyright = (bool) preg_match('/\/\*(?:.|\R)*?Copyright \(c\) 2025 Oleksandr Tishchenko \/ Marketing America Corp(?:.|\R)*?\*\//u', $content);

    $needsRewrite = $hasBlockCopyright;

    if (! $needsRewrite) {
        if (str_contains($content, CANONICAL_COPYRIGHT)) {
            $needsRewrite = ! preg_match('/<\?php\R' . preg_quote(CANONICAL_COPYRIGHT, '/') . '\R(?:declare\(strict_types=1\);\R)?/u', $content);
        }
    }

    return ['hasBlockCopyright' => $hasBlockCopyright, 'needsRewrite' => $needsRewrite];
}

function rewriteFile(string $content): string
{
    $updated = preg_replace('/\R?\/\*(?:.|\R)*?Copyright \(c\) 2025 Oleksandr Tishchenko \/ Marketing America Corp(?:.|\R)*?\*\/\R?/u', "\n", $content, 1);
    if ($updated === null) {
        return $content;
    }

    $updated = preg_replace('/\R?' . preg_quote(CANONICAL_COPYRIGHT, '/') . '\R?/u', "\n", $updated, 1) ?? $updated;

    if (! str_starts_with($updated, "<?php")) {
        return $content;
    }

    $afterPhp = substr($updated, 5);
    if ($afterPhp === false) {
        return $content;
    }

    $afterPhp = ltrim($afterPhp, "\r\n");

    if (str_starts_with($afterPhp, 'declare(strict_types=1);')) {
        $afterDeclare = substr($afterPhp, strlen('declare(strict_types=1);'));
        $afterDeclare = ltrim($afterDeclare === false ? '' : $afterDeclare, "\r\n");
        return "<?php\n" . CANONICAL_COPYRIGHT . "\ndeclare(strict_types=1);\n\n" . $afterDeclare;
    }

    return "<?php\n" . CANONICAL_COPYRIGHT . "\n" . $afterPhp;
}

function backupFile(string $filePath, string $content, string $backupDir): void
{
    $normalizedPath = ltrim(str_replace('\\', '/', $filePath), '/');
    $destination = rtrim($backupDir, '/') . '/' . $normalizedPath;
    $directory = dirname($destination);

    if (! is_dir($directory)) {
        mkdir($directory, 0777, true);
    }

    file_put_contents($destination, $content);
}
