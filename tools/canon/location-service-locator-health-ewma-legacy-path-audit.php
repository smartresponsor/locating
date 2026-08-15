<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$old = $root.'/src/Service/Locator/HealthEwma.php';
$new = $root.'/src/Service/Provider/Location/HealthEwmaService.php';
$providerOrder = $root.'/src/Service/Provider/Location/ProviderOrderService.php';
$adaptiveOrdering = $root.'/src/Service/Locator/AdaptiveOrdering.php';
$routerOrchestrator = $root.'/src/Service/Locator/RouterOrchestrator.php';
$errors = [];

if (is_file($old)) {
    $errors[] = 'Legacy health EWMA path still exists: src/Service/Locator/HealthEwma.php';
}

if (!is_file($new)) {
    $errors[] = 'Canonical health EWMA service is missing: src/Service/Provider/Location/HealthEwmaService.php';
} else {
    $contents = (string) file_get_contents($new);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'HealthEwmaService must use App\Locating\Service\Provider\Location namespace.';
    }
    if (!str_contains($contents, 'final class HealthEwmaService')) {
        $errors[] = 'Health EWMA class must be named HealthEwmaService.';
    }
    if (!str_contains($contents, 'HealthEwmaLegacyInterface')) {
        $errors[] = 'HealthEwmaService must keep the legacy bridge contract.';
    }
}

foreach ([$providerOrder, $adaptiveOrdering, $routerOrchestrator] as $consumer) {
    if (!is_file($consumer)) {
        $errors[] = 'Expected LC-34 consumer is missing: '.substr($consumer, strlen($root) + 1);
        continue;
    }

    $contents = (string) file_get_contents($consumer);
    if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\HealthEwma')) {
        $errors[] = 'Consumer still imports legacy HealthEwma: '.substr($consumer, strlen($root) + 1);
    }
    if (str_contains($contents, 'private HealthEwma $health')) {
        $errors[] = 'Consumer still type-hints legacy HealthEwma: '.substr($consumer, strlen($root) + 1);
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents($reportDir.'/locating-lc34-service-locator-health-ewma-legacy-path.json', json_encode([
    'wave' => 'LC-34',
    'old_path' => 'src/Service/Locator/HealthEwma.php',
    'new_path' => 'src/Service/Provider/Location/HealthEwmaService.php',
    'updated_consumers' => [
        'src/Service/Provider/Location/ProviderOrderService.php',
        'src/Service/Locator/AdaptiveOrdering.php',
        'src/Service/Locator/RouterOrchestrator.php',
    ],
    'errors' => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-34] '.$error.PHP_EOL);
    }
    exit(1);
}

echo "[LC-34] Health EWMA legacy path canon passed.
";
