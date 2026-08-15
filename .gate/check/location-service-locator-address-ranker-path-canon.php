<?php

declare(strict_types=1);

$root = dirname(__DIR__, 2);
$tool = $root . '/tools/canon/location-service-locator-address-ranker-path-audit.php';

if (!is_file($tool)) {
    fwrite(STDERR, "Missing LC-18 audit tool: {$tool}" . PHP_EOL);
    exit(1);
}

require $tool;
