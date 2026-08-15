#!/usr/bin/env php
<?php
declare(strict_types=1);
$root = dirname(__DIR__, 2);
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root . '/src', FilesystemIterator::SKIP_DOTS));
$count = 0;
$sample = [];
foreach ($iterator as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') { continue; }
    $content = file_get_contents($file->getPathname());
    if ($content !== false && preg_match('/^namespace\s+App\Locating\\/m', $content) === 1) {
        $count++;
        if (count($sample) < 50) {
            $sample[] = str_replace($root . DIRECTORY_SEPARATOR, '', $file->getPathname());
        }
    }
}
echo 'Smartresponsor namespace declarations: ' . $count . PHP_EOL;
foreach ($sample as $path) {
    echo ' - ' . $path . PHP_EOL;
}
if ($count > 0) {
    fwrite(STDERR, 'Bridge shutdown is NOT safe yet: Smartresponsor namespaces still exist.' . PHP_EOL);
    exit(1);
}
echo 'Bridge shutdown readiness: SAFE' . PHP_EOL;
