<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacy = $root.'/src/Service/Locator/CostCapPolicy.php';
$target = $root.'/src/Service/Provider/Location/CostCapPolicyService.php';
$errors = [];

if (is_file($legacy)) {
    $errors[] = 'Legacy CostCapPolicy path still exists: src/Service/Locator/CostCapPolicy.php';
}

if (!is_file($target)) {
    $errors[] = 'Canonical CostCapPolicyService path is missing.';
} else {
    $contents = (string) file_get_contents($target);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'CostCapPolicyService namespace is not canonical.';
    }
    if (!str_contains($contents, 'final class CostCapPolicyService')) {
        $errors[] = 'CostCapPolicyService class declaration is missing.';
    }
    if (!str_contains($contents, 'CostCapPolicyLegacyInterface')) {
        $errors[] = 'CostCapPolicyService must keep the bridge legacy interface contract.';
    }
    if (str_contains($contents, 'final class CostCapPolicy ')) {
        $errors[] = 'CostCapPolicyService must not keep the generic CostCapPolicy class name.';
    }
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-42] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[LC-42] CostCapPolicy legacy path normalization is canonical.'.PHP_EOL;
