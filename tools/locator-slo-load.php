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
 * Simple SLO load runner for Locator.
 *
 * It uses the golden fixture set:
 *
 *   tests/Locator/Fixture/address-golden.ndjson
 *
 * and performs multiple passes over suggest/reverse records, measuring latency
 * and error rate. At the end it computes p95 latency and error rate and
 * returns a non-zero exit code when SLO targets are not met.
 *
 * Environment variables:
 *
 *   LOCATOR_BASE_URL       Base URL (default: http://localhost:8000)
 *   LOCATOR_TENANT         Tenant id header (default: demo)
 *   LOCATOR_SLO_ITERATIONS Number of passes over the fixture set (default: 5)
 *   LOCATOR_SLO_P95_MS     Max allowed p95 latency in ms (default: 700)
 *   LOCATOR_SLO_ERROR_RATE Max allowed error rate in percent (default: 0.5)
 */

$baseUrl = getenv('LOCATOR_BASE_URL') ?: 'http://localhost:8000';
$tenant = getenv('LOCATOR_TENANT') ?: 'demo';

$iterations = (int)(getenv('LOCATOR_SLO_ITERATIONS') ?: '5');
if ($iterations < 1) {
    $iterations = 1;
}

$maxP95Ms = (float)(getenv('LOCATOR_SLO_P95_MS') ?: '700');
$maxErrorRate = (float)(getenv('LOCATOR_SLO_ERROR_RATE') ?: '0.5');

$fixturePath = __DIR__ . '/../tests/Locator/Fixture/address-golden.ndjson';

if (!is_file($fixturePath)) {
    fwrite(STDERR, "Fixture file not found: {$fixturePath}\n");
    exit(1);
}

fwrite(STDOUT, sprintf(
    "Locator SLO load run\nBase URL : %s\nTenant   : %s\nFile     : %s\nIterations: %d\nMax p95  : %.1f ms\nMax err  : %.3f %%\n\n",
    $baseUrl,
    $tenant,
    $fixturePath,
    $iterations,
    $maxP95Ms,
    $maxErrorRate
));

$records = load_fixture_records($fixturePath);

// Filter only suggest/reverse records, skip intentionally invalid ones.
$records = array_values(array_filter(
    $records,
    static function (array $record): bool {
        $kind = $record['kind'] ?? '';
        if ($kind === 'suggest' || $kind === 'reverse') {
            return true;
        }

        return false;
    }
));

if ($records === []) {
    fwrite(STDERR, "No suitable records found in fixture file.\n");
    exit(1);
}

$latencies = [];
$successFlags = [];

for ($i = 0; $i < $iterations; $i++) {
    foreach ($records as $record) {
        $kind = (string)($record['kind'] ?? 'unknown');
        $input = (array)($record['input'] ?? []);
        $id = (string)($record['id'] ?? 'unknown');

        $url = build_url($baseUrl, $kind, $input);

        $context = stream_context_create([
            'http' => [
                'method' => 'GET',
                'header' => [
                    'Accept: application/json',
                    'X-SR-Tenant: ' . $tenant,
                ],
                'ignore_errors' => true,
                'timeout' => 10,
            ],
        ]);

        $start = microtime(true);
        $body = @file_get_contents($url, false, $context);
        $durationMs = (microtime(true) - $start) * 1000.0;

        $statusLine = $http_response_header[0] ?? 'HTTP/1.1 000 Unknown';
        $statusCode = (int)preg_replace('/[^0-9]/', '', substr($statusLine, 9, 3));

        $latencies[] = $durationMs;

        $ok = $statusCode >= 200 && $statusCode < 500 && $body !== false;
        $successFlags[] = $ok;

        fwrite(STDOUT, sprintf(
            "[%d] %s (%s) -> %s | %s | %.1f ms\n",
            $i + 1,
            $id,
            $kind,
            $url,
            $statusLine,
            $durationMs
        ));
    }
}

$total = count($latencies);
$errors = 0;

foreach ($successFlags as $flag) {
    if (!$flag) {
        $errors++;
    }
}

$errorRate = $total > 0 ? ($errors / $total) * 100.0 : 0.0;

sort($latencies, SORT_NUMERIC);
if ($total > 0) {
    $index = (int)ceil(0.95 * $total) - 1;
    if ($index < 0) {
        $index = 0;
    } elseif ($index >= $total) {
        $index = $total - 1;
    }
    $p95 = $latencies[$index];
} else {
    $p95 = 0.0;
}

fwrite(STDOUT, "\nSummary:\n");
fwrite(STDOUT, sprintf("  requests   : %d\n", $total));
fwrite(STDOUT, sprintf("  errors     : %d\n", $errors));
fwrite(STDOUT, sprintf("  error rate : %.3f %%\n", $errorRate));
fwrite(STDOUT, sprintf("  p95        : %.1f ms\n", $p95));

$okP95 = $p95 <= $maxP95Ms;
$okError = $errorRate <= $maxErrorRate;

if ($okP95 && $okError) {
    fwrite(STDOUT, "Result: PASS (SLO targets met).\n");
    exit(0);
}

fwrite(STDOUT, "Result: FAIL (SLO targets not met).\n");
exit(1);

function load_fixture_records(string $path): array
{
    $records = [];

    $handle = fopen($path, 'rb');
    if (!is_resource($handle)) {
        return $records;
    }

    while (($line = fgets($handle)) !== false) {
        $line = trim($line);
        if ($line === '') {
            continue;
        }

        $decoded = json_decode($line, true);
        if (!is_array($decoded)) {
            continue;
        }

        $records[] = $decoded;
    }

    fclose($handle);

    return $records;
}

function build_url(string $baseUrl, string $kind, array $input): string
{
    $baseUrl = rtrim($baseUrl, '/');

    if ($kind === 'status') {
        return $baseUrl . '/locator/status';
    }

    if ($kind === 'suggest') {
        $query = [
            'query' => (string)($input['query'] ?? ''),
            'country' => (string)($input['country'] ?? ''),
            'limit' => (string)($input['limit'] ?? '5'),
            'locale' => (string)($input['locale'] ?? ''),
        ];

        return $baseUrl . '/locator/address/suggest?' . http_build_query($query);
    }

    if ($kind === 'reverse') {
        $query = [
            'lat' => (string)($input['lat'] ?? ''),
            'lon' => (string)($input['lon'] ?? ''),
        ];

        if (isset($input['country'])) {
            $query['country'] = (string)$input['country'];
        }

        return $baseUrl . '/locator/address/reverse?' . http_build_query($query);
    }

    // Fallback – treat as status.
    return $baseUrl . '/locator/status';
}
