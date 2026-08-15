<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

$root = dirname(__DIR__, 2);
$failures = [];

$forbiddenPaths = [
    'src/Service/Locator/SuggestRanker.php',
    'src/ServiceInterface/Locator/SuggestRankerInterface.php',
];

$requiredFiles = [
    'src/Service/Address/Location/AddressSuggestRankerLegacyService.php' => [
        'namespace App\Locating\\Service\\Address\\Location;',
        'final class AddressSuggestRankerLegacyService implements AddressSuggestRankerLegacyServiceInterface',
        'use App\Locating\\ServiceInterface\\Address\\Location\\AddressSuggestRankerLegacyServiceInterface;',
    ],
    'src/ServiceInterface/Address/Location/AddressSuggestRankerLegacyServiceInterface.php' => [
        'namespace App\Locating\\ServiceInterface\\Address\\Location;',
        'interface AddressSuggestRankerLegacyServiceInterface',
    ],
];

foreach ($forbiddenPaths as $path) {
    if (is_file($root . DIRECTORY_SEPARATOR . $path)) {
        $failures[] = sprintf('Legacy locator suggest ranker path must be retired: %s', $path);
    }
}

foreach ($requiredFiles as $path => $needles) {
    $absolutePath = $root . DIRECTORY_SEPARATOR . $path;
    if (!is_file($absolutePath)) {
        $failures[] = sprintf('Required canonical suggest ranker file is missing: %s', $path);
        continue;
    }

    $contents = (string) file_get_contents($absolutePath);
    foreach ($needles as $needle) {
        if (!str_contains($contents, $needle)) {
            $failures[] = sprintf('Required marker not found in %s: %s', $path, $needle);
        }
    }
}

if ([] !== $failures) {
    fwrite(STDERR, "Locating LC-25 service locator address suggest ranker legacy path audit failed:\n");
    foreach ($failures as $failure) {
        fwrite(STDERR, ' - ' . $failure . "\n");
    }

    exit(1);
}

fwrite(STDOUT, "Locating LC-25 service locator address suggest ranker legacy path audit passed.\n");
