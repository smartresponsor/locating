<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$old = $root.'/src/Service/Locator/ProviderOrder.php';
$new = $root.'/src/Service/Provider/Location/ProviderOrderService.php';
$errors = [];

if (is_file($old)) {
    $errors[] = 'Legacy provider order path still exists: src/Service/Locator/ProviderOrder.php';
}

if (!is_file($new)) {
    $errors[] = 'Canonical provider order service is missing: src/Service/Provider/Location/ProviderOrderService.php';
} else {
    $contents = (string) file_get_contents($new);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'ProviderOrderService must use App\Locating\Service\Provider\Location namespace.';
    }
    if (!str_contains($contents, 'final class ProviderOrderService')) {
        $errors[] = 'Provider order class must be named ProviderOrderService.';
    }
    if (!str_contains($contents, 'ProviderOrderLegacyInterface')) {
        $errors[] = 'ProviderOrderService must keep the legacy bridge contract.';
    }
    if (!str_contains($contents, 'use App\Locating\Service\Provider\Location\Runtime\ScoreEnsemble;')) {
        $errors[] = 'ProviderOrderService must explicitly import ScoreEnsemble while that collaborator remains in the legacy locator namespace.';
    }
    if (!str_contains($contents, 'use App\Locating\Service\Provider\Location\Runtime\HealthEwma;')) {
        $errors[] = 'ProviderOrderService must explicitly import HealthEwma while that collaborator remains in the legacy locator namespace.';
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents($reportDir.'/locating-lc33-service-locator-provider-order-legacy-path.json', json_encode([
    'wave' => 'LC-33',
    'old_path' => 'src/Service/Locator/ProviderOrder.php',
    'new_path' => 'src/Service/Provider/Location/ProviderOrderService.php',
    'errors' => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-33] '.$error.PHP_EOL);
    }
    exit(1);
}

echo "[LC-33] Provider order legacy path canon passed.
";
