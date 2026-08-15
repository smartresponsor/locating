<?php

declare(strict_types=1);

/**
 * LC-28 canon gate for the Here provider legacy path normalization.
 *
 * The gate is intentionally narrow: it validates that the provider moved to the
 * canonical Provider/Location service layer and that the old Locator path is no
 * longer active after the touched-file patch is applied.
 */

$root = dirname(__DIR__, 2);
$legacyPath = $root.'/src/Service/Locator/Provider/HereProvider.php';
$newPath = $root.'/src/Service/Provider/Location/HereProviderService.php';
$routerPath = $root.'/src/Service/Locator/Provider/ProviderRouter.php';
$reportDir = $root.'/report';

if (!is_dir($reportDir)) {
    mkdir($reportDir, 0777, true);
}

$issues = [];

if (is_file($legacyPath)) {
    $issues[] = 'Legacy HereProvider path is still present: src/Service/Locator/Provider/HereProvider.php';
}

if (!is_file($newPath)) {
    $issues[] = 'Canonical HereProviderService path is missing.';
} else {
    $content = file_get_contents($newPath) ?: '';
    if (!str_contains($content, 'namespace App\Locating\\Service\\Provider\\Location;')) {
        $issues[] = 'HereProviderService does not use App\Locating\\Service\\Provider\\Location namespace.';
    }
    if (!str_contains($content, 'class HereProviderService')) {
        $issues[] = 'Here provider class was not renamed to HereProviderService.';
    }
    if (!str_contains($content, 'HereProviderLegacyInterface')) {
        $issues[] = 'HereProviderService no longer exposes the bridge legacy contract.';
    }
}

if (!is_file($routerPath)) {
    $issues[] = 'ProviderRouter is missing; consumer wiring cannot be verified.';
} else {
    $router = file_get_contents($routerPath) ?: '';
    if (!str_contains($router, 'use App\Locating\\Service\\Provider\\Location\\HereProviderService;')) {
        $issues[] = 'ProviderRouter does not import HereProviderService.';
    }
    if (!str_contains($router, 'private HereProviderService $here;')) {
        $issues[] = 'ProviderRouter $here property was not retargeted to HereProviderService.';
    }
    if (!str_contains($router, '$this->here = new HereProviderService($env);')) {
        $issues[] = 'ProviderRouter still instantiates the legacy HereProvider class.';
    }
}

$report = [
    'stage' => 'LC-28',
    'subject' => 'service-locator-provider-here-legacy-path',
    'legacy_path' => 'src/Service/Locator/Provider/HereProvider.php',
    'canonical_path' => 'src/Service/Provider/Location/HereProviderService.php',
    'consumer' => 'src/Service/Locator/Provider/ProviderRouter.php',
    'issue_count' => count($issues),
    'issues' => $issues,
];

file_put_contents($reportDir.'/locating-service-locator-provider-here-legacy-path-latest.json', json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

if ($issues !== []) {
    fwrite(STDERR, "LC-28 provider Here legacy path canon failed:\n- ".implode("\n- ", $issues)."\n");
    exit(1);
}

echo "LC-28 provider Here legacy path canon passed.\n";