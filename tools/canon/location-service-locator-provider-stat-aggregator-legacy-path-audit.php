<?php

declare(strict_types=1);

/**
 * LC-36 canon gate for ProviderStatAggregator legacy path normalization.
 *
 * This gate is intentionally narrow: it validates only the touched legacy provider
 * statistic aggregator migration from App\Locating\Service\Locator to the
 * Symfony-oriented App\Locating\Service\Provider\Location service layer.
 */

$root = dirname(__DIR__, 2);
$newPath = $root.'/src/Service/Provider/Location/ProviderStatAggregatorService.php';
$oldPath = $root.'/src/Service/Locator/ProviderStatAggregator.php';
$errors = [];

if (!is_file($newPath)) {
    $errors[] = 'Missing canonical ProviderStatAggregatorService path: '.$newPath;
} else {
    $contents = (string) file_get_contents($newPath);
    foreach ([
        'namespace App\Locating\\Service\\Provider\\Location;' => 'canonical App service provider namespace',
        'final class ProviderStatAggregatorService implements ProviderStatAggregatorInterface' => 'canonical class name and bridge contract',
        'use App\Locating\\Bridge\\Legacy\\Provider\\Location\\ProviderStatAggregatorInterface;' => 'provider bridge contract import',
    ] as $needle => $label) {
        if (!str_contains($contents, $needle)) {
            $errors[] = 'ProviderStatAggregatorService is missing '.$label.'.';
        }
    }
    if (str_contains($contents, 'namespace App\Locating\\Service\\Locator;')) {
        $errors[] = 'ProviderStatAggregatorService still declares legacy Smartresponsor namespace.';
    }
    if (str_contains($contents, 'final class ProviderStatAggregator implements')) {
        $errors[] = 'ProviderStatAggregatorService still uses the old class name.';
    }
}

if (is_file($oldPath)) {
    $errors[] = 'Legacy ProviderStatAggregator path still exists and must be retired with backup: '.$oldPath;
}

if ($errors !== []) {
    fwrite(STDERR, "LC-36 ProviderStatAggregator legacy path canon failed:
");
    foreach ($errors as $error) {
        fwrite(STDERR, ' - '.$error."
");
    }
    exit(1);
}

echo "LC-36 ProviderStatAggregator legacy path canon passed.
";
