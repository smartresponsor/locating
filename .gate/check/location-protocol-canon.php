<?php
declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

$root = dirname(__DIR__, 2);

$forbiddenDirectories = [
    'src/Bundle',
    'src/Console',
    'src/Contract',
    'src/Domain',
    'src/DomainInterface',
    'src/Integration',
    'src/Model',
    'src/Strategy',
    'src/Port',
    'src/Adaptor',
    'src/Infra',
    'src/opr',
    'src/Legacy',
];

$violations = [];


$composerPath = $root . '/composer.json';
if (!is_file($composerPath)) {
    $violations[] = 'Missing composer.json';
} else {
    $composerRaw = file_get_contents($composerPath);
    $composer = is_string($composerRaw) ? json_decode($composerRaw, true) : null;

    if (!is_array($composer)) {
        $violations[] = 'composer.json is unreadable or invalid JSON';
    } else {
        if (($composer['name'] ?? null) !== 'locating/location') {
            $violations[] = 'composer.json package name must be locating/location';
        }

        if (($composer['type'] ?? null) !== 'project') {
            $violations[] = 'composer.json type must be project';
        }

        if (($composer['require']['php'] ?? null) !== '^8.4') {
            $violations[] = 'composer.json PHP requirement must be ^8.4';
        }

        $autoload = $composer['autoload']['psr-4'] ?? [];
        if (!is_array($autoload) || !array_key_exists('App\\', $autoload) || $autoload['App\\'] !== 'src/') {
            $violations[] = 'composer.json must expose App\ as src/';
        }
    }
}


foreach ($forbiddenDirectories as $directory) {
    $absolutePath = $root . DIRECTORY_SEPARATOR . $directory;

    if (is_dir($absolutePath)) {
        $violations[] = sprintf('Forbidden directory present: %s', $directory);
    }
}

$srcIterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($root . '/src', FilesystemIterator::SKIP_DOTS)
);

foreach ($srcIterator as $fileInfo) {
    if (!$fileInfo->isFile() || $fileInfo->getExtension() !== 'php') {
        continue;
    }

    $relativePath = ltrim(str_replace($root, '', $fileInfo->getPathname()), DIRECTORY_SEPARATOR);
    $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);
    $contents = file_get_contents($fileInfo->getPathname());

    if ($contents === false) {
        $violations[] = sprintf('Unreadable file: %s', $relativePath);
        continue;
    }

    if (preg_match('/\bnamespace\s+App\\\\/', $contents) !== 1) {
        $violations[] = sprintf('Namespace is not rooted in App\\: %s', $relativePath);
    }

    $pathSegments = explode('/', $relativePath);
    $locationIndexes = [];
    foreach ($pathSegments as $index => $segment) {
        if ($segment === 'Location') {
            $locationIndexes[] = $index;
        }
    }

    foreach ($locationIndexes as $locationIndex) {
        $isEntityException = isset($pathSegments[1], $pathSegments[2])
            && $pathSegments[0] === 'src'
            && $pathSegments[1] === 'Entity'
            && $pathSegments[2] === 'Location';

        $isDeepEnough = $locationIndex >= 3;

        if (!$isEntityException && !$isDeepEnough) {
            $violations[] = sprintf('Location tail is too shallow in src/: %s', $relativePath);
            break;
        }
    }

    if (preg_match('/\b(?:TODO|stub)\b/i', $contents) === 1) {
        $violations[] = sprintf('Forbidden TODO/stub token found: %s', $relativePath);
    }

    if (preg_match('/catch\s*\([^)]*\)\s*\{\s*\}/s', $contents) === 1) {
        $violations[] = sprintf('Empty catch block found: %s', $relativePath);
    }
}

$testsPath = $root . '/tests';
if (is_dir($testsPath)) {
    $testsIterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($testsPath, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($testsIterator as $fileInfo) {
        if (!$fileInfo->isFile()) {
            continue;
        }

        $relativePath = ltrim(str_replace($root, '', $fileInfo->getPathname()), DIRECTORY_SEPARATOR);
        $relativePath = str_replace(DIRECTORY_SEPARATOR, '/', $relativePath);

        if (preg_match('#^tests/(?:Location|Locating)(?:/|$)#', $relativePath) === 1) {
            $violations[] = sprintf('Forbidden tests tail found: %s', $relativePath);
        }

        if (preg_match('#^tests/.*/(?:Location|Locating)/#', $relativePath) === 1) {
            $violations[] = sprintf('Forbidden nested tests tail found: %s', $relativePath);
        }
    }
}

if ($violations === []) {
    fwrite(STDOUT, "Location protocol canon check: PASS\n");
    exit(0);
}

fwrite(STDOUT, "Location protocol canon check: FAIL\n");
foreach ($violations as $violation) {
    fwrite(STDOUT, '- ' . $violation . PHP_EOL);
}

exit(1);
