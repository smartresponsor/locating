<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$oldService = $root.'/src/Service/Locator/ScoreEnsemble.php';
$oldInterface = $root.'/src/ServiceInterface/Locator/ScoreEnsembleInterface.php';
$newService = $root.'/src/Service/Provider/Location/ScoreEnsembleService.php';
$newInterface = $root.'/src/ServiceInterface/Provider/Location/ScoreEnsembleServiceInterface.php';
$providerOrder = $root.'/src/Service/Provider/Location/ProviderOrderService.php';
$errors = [];

foreach ([
    'src/Service/Locator/ScoreEnsemble.php' => $oldService,
    'src/ServiceInterface/Locator/ScoreEnsembleInterface.php' => $oldInterface,
] as $label => $path) {
    if (is_file($path)) {
        $errors[] = 'Legacy score ensemble path still exists: '.$label;
    }
}

if (!is_file($newService)) {
    $errors[] = 'Canonical score ensemble service is missing: src/Service/Provider/Location/ScoreEnsembleService.php';
} else {
    $contents = (string) file_get_contents($newService);
    if (!str_contains($contents, 'namespace App\Locating\Service\Provider\Location;')) {
        $errors[] = 'ScoreEnsembleService must use App\Locating\Service\Provider\Location namespace.';
    }
    if (!str_contains($contents, 'final class ScoreEnsembleService')) {
        $errors[] = 'Score ensemble class must be named ScoreEnsembleService.';
    }
    if (!str_contains($contents, 'ScoreEnsembleServiceInterface')) {
        $errors[] = 'ScoreEnsembleService must implement ScoreEnsembleServiceInterface.';
    }
}

if (!is_file($newInterface)) {
    $errors[] = 'Canonical score ensemble interface is missing: src/ServiceInterface/Provider/Location/ScoreEnsembleServiceInterface.php';
} else {
    $contents = (string) file_get_contents($newInterface);
    if (!str_contains($contents, 'namespace App\Locating\ServiceInterface\Provider\Location;')) {
        $errors[] = 'ScoreEnsembleServiceInterface must use App\Locating\ServiceInterface\Provider\Location namespace.';
    }
    if (!str_contains($contents, 'interface ScoreEnsembleServiceInterface')) {
        $errors[] = 'Score ensemble interface must be named ScoreEnsembleServiceInterface.';
    }
}

if (!is_file($providerOrder)) {
    $errors[] = 'Expected provider order consumer is missing: src/Service/Provider/Location/ProviderOrderService.php';
} else {
    $contents = (string) file_get_contents($providerOrder);
    if (str_contains($contents, 'App\Locating\Service\Provider\Location\Runtime\ScoreEnsemble')) {
        $errors[] = 'ProviderOrderService still imports legacy ScoreEnsemble.';
    }
    if (str_contains($contents, 'private ScoreEnsemble $ensemble')) {
        $errors[] = 'ProviderOrderService still type-hints legacy ScoreEnsemble.';
    }
    if (!str_contains($contents, 'private ScoreEnsembleService $ensemble')) {
        $errors[] = 'ProviderOrderService must type-hint ScoreEnsembleService.';
    }
}

$reportDir = $root.'/report';
if (!is_dir($reportDir)) {
    mkdir($reportDir, 0775, true);
}
file_put_contents($reportDir.'/locating-lc35-service-locator-score-ensemble-legacy-path.json', json_encode([
    'wave' => 'LC-35',
    'old_paths' => [
        'src/Service/Locator/ScoreEnsemble.php',
        'src/ServiceInterface/Locator/ScoreEnsembleInterface.php',
    ],
    'new_paths' => [
        'src/Service/Provider/Location/ScoreEnsembleService.php',
        'src/ServiceInterface/Provider/Location/ScoreEnsembleServiceInterface.php',
    ],
    'updated_consumers' => [
        'src/Service/Provider/Location/ProviderOrderService.php',
    ],
    'errors' => $errors,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES).PHP_EOL);

if ([] !== $errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, '[LC-35] '.$error.PHP_EOL);
    }
    exit(1);
}

echo "[LC-35] Score ensemble legacy path canon passed.
";
