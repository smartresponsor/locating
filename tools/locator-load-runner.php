<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

if (PHP_SAPI !== 'cli') {
    fwrite(STDERR, "This script must be run from CLI.\n");
    exit(1);
}

/**
 * Minimal load runner for Locator HTTP endpoints.
 *
 * It does not try to be extremely smart or concurrent, but it is
 * good enough for local SLO smoke and regression checks.
 *
 * Usage example:
 *   php tools/locator-load-runner.php
 *
 * Environment variables:
 *   LOCATOR_BASE_URL   Base URL (default: http://localhost:8000)
 *   LOCATOR_REQUESTS   Total number of requests (default: 200)
 */

$baseUrl = getenv('LOCATOR_BASE_URL') ?: 'http://localhost:8000';
$totalRequests = (int)($argv[1] ?? getenv('LOCATOR_REQUESTS') ?: '200');

if ($totalRequests <= 0) {
    $totalRequests = 200;
}

$endpoints = [
    'status' => '/locator/status',
    'suggest' => '/locator/address/suggest',
    'reverse' => '/locator/address/reverse',
];

$stats = [
    'total' => 0,
    'success' => 0,
    'error' => 0,
    'latency_ms' => [],
];

fwrite(STDOUT, sprintf("Running %d requests against %s\n", $totalRequests, $baseUrl));

for ($i = 0; $i < $totalRequests; $i++) {
    $mode = array_keys($endpoints)[$i % count($endpoints)];
    $path = $endpoints[$mode];

    $url = rtrim($baseUrl, '/') . $path;

    if ($mode === 'suggest') {
        $query = http_build_query([
            'query' => 'Main Street Houston',
            'country' => 'US',
            'limit' => 5,
            'locale' => 'en_US',
        ]);
        $url .= '?' . $query;
    }

    if ($mode === 'reverse') {
        $query = http_build_query([
            'lat' => '29.7604',
            'lon' => '-95.3698',
            'country' => 'US',
        ]);
        $url .= '?' . $query;
    }

    $start = microtime(true);
    $response = @file_get_contents($url);
    $durationMs = (microtime(true) - $start) * 1000.0;

    $stats['latency_ms'][] = $durationMs;
    $stats['total']++;

    if ($response === false) {
        $stats['error']++;
        fwrite(STDERR, sprintf("[error] %s %s in %.1f ms\n", $mode, $url, $durationMs));
        continue;
    }

    $stats['success']++;
}

sort($stats['latency_ms']);
$p95 = $stats['latency_ms'] ? percentile($stats['latency_ms'], 0.95) : 0.0;

$errorRate = $stats['total'] > 0
    ? $stats['error'] / $stats['total']
    : 0.0;

fwrite(STDOUT, "\nSummary:\n");
fwrite(STDOUT, sprintf("  total   : %d\n", $stats['total']));
fwrite(STDOUT, sprintf("  success : %d\n", $stats['success']));
fwrite(STDOUT, sprintf("  error   : %d\n", $stats['error']));
fwrite(STDOUT, sprintf("  p95     : %.1f ms\n", $p95));
fwrite(STDOUT, sprintf("  error%%  : %.2f%%\n", $errorRate * 100.0));

if ($p95 <= 700.0 && $errorRate <= 0.005) {
    fwrite(STDOUT, "SLO OK: p95 <= 700 ms and error rate <= 0.5%\n");
    exit(0);
}

fwrite(STDOUT, "SLO FAILED: see numbers above.\n");
exit(2);

/**
 * @param float[] $values
 */
function percentile(array $values, float $p): float
{
    $n = count($values);
    if ($n === 0) {
        return 0.0;
    }

    $p = max(0.0, min(1.0, $p));
    $index = ($n - 1) * $p;
    $lower = (int)floor($index);
    $upper = (int)ceil($index);

    if ($lower === $upper) {
        return $values[$lower];
    }

    $weight = $index - $lower;

    return $values[$lower] * (1.0 - $weight) + $values[$upper] * $weight;
}
