<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$old = $root.'/src/Service/Locator/ProviderAuthzPolicy.php';
$new = $root.'/src/Service/Provider/Location/ProviderAuthzPolicyService.php';
$errors = [];

if (is_file($old)) {
    $errors[] = 'Legacy provider authz policy path still exists: src/Service/Locator/ProviderAuthzPolicy.php';
}

if (!is_file($new)) {
    $errors[] = 'Canonical provider authz policy service is missing: src/Service/Provider/Location/ProviderAuthzPolicyService.php';
} else {
    $contents = (string) file_get_contents($new);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'ProviderAuthzPolicyService must use App\Locating\Service\Provider\Location namespace.';
    }
    if (!str_contains($contents, 'final class ProviderAuthzPolicyService')) {
        $errors[] = 'Provider authz policy class must be named ProviderAuthzPolicyService.';
    }
    if (!str_contains($contents, 'ProviderAuthzPolicyLegacyInterface')) {
        $errors[] = 'ProviderAuthzPolicyService must keep the legacy bridge contract.';
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents($reportDir.'/locating-lc32-service-locator-provider-authz-policy-legacy-path.json', json_encode([
    'wave' => 'LC-32',
    'old_path' => 'src/Service/Locator/ProviderAuthzPolicy.php',
    'new_path' => 'src/Service/Provider/Location/ProviderAuthzPolicyService.php',
    'errors' => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-32] '.$error.PHP_EOL);
    }
    exit(1);
}

echo "[LC-32] Provider authz policy legacy path canon passed.
";
