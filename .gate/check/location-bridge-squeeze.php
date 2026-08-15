<?php
declare(strict_types=1);

$root = dirname(__DIR__, 2);
$src = $root . '/src';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src));
$appFiles = [];
$blockers = [];
$edgeAdapters = [];

foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') {
        continue;
    }
    $path = str_replace('\\', '/', substr($file->getPathname(), strlen($root) + 1));
    $contents = file_get_contents($file->getPathname());
    if ($contents === false || !preg_match('/namespace\s+([^;]+);/', $contents, $nsMatch)) {
        continue;
    }
    $namespace = $nsMatch[1];
    if (!str_starts_with($namespace, 'App\Locating\\')) {
        continue;
    }
    preg_match_all('/^use\s+(App\Locating\\[^;]+);/m', $contents, $matches);
    preg_match_all('/App\Locating\\[A-Za-z0-9_\\]+/', $contents, $inlineMatches);
    $refs = array_values(array_unique(array_merge($matches[1], $inlineMatches[0])));
    if ($refs === []) {
        continue;
    }
    $appFiles[] = ['path' => $path, 'refs' => $refs];
    $isEdge = str_contains($path, '/Infrastructure/') && str_contains(basename($path), 'Smartresponsor')
        || str_ends_with($path, 'SmartresponsorLocationQuotaGuardBackend.php');
    if ($isEdge) {
        $edgeAdapters[] = $path;
    } else {
        $blockers[] = $path;
    }
}

sort($edgeAdapters);
sort($blockers);

echo 'App files with legacy refs: ' . count($appFiles) . PHP_EOL;
echo 'Edge adapters: ' . count($edgeAdapters) . PHP_EOL;
echo 'Residual blockers: ' . count($blockers) . PHP_EOL;
foreach ($blockers as $blocker) {
    echo ' - ' . $blocker . PHP_EOL;
}

exit($blockers === [] ? 0 : 1);
