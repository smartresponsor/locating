<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$legacy = $root.'/src/Service/Locator/BudgetGuard.php';
$target = $root.'/src/Service/Provider/Location/BudgetGuardService.php';
$errors = [];

if (is_file($legacy)) {
    $errors[] = 'Legacy BudgetGuard path still exists: src/Service/Locator/BudgetGuard.php';
}

if (!is_file($target)) {
    $errors[] = 'Canonical BudgetGuardService path is missing.';
} else {
    $contents = (string) file_get_contents($target);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'BudgetGuardService namespace is not canonical.';
    }
    if (!str_contains($contents, 'final class BudgetGuardService')) {
        $errors[] = 'BudgetGuardService class declaration is missing.';
    }
    if (!str_contains($contents, 'BudgetGuardLegacyInterface')) {
        $errors[] = 'BudgetGuardService must keep the bridge legacy interface contract.';
    }
}

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-41] '.$error.PHP_EOL);
    }
    exit(1);
}

echo '[LC-41] BudgetGuard legacy path normalization is canonical.'.PHP_EOL;
