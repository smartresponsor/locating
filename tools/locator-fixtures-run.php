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
 * Golden fixture runner for Locator.
 *
 * It sends HTTP requests to a running Locator instance using the golden
 * fixture set defined in:
 *
 *   tests/Locator/Fixture/address-golden.ndjson
 *
 * Usage:
 *   php tools/locator-fixtures-run.php
 *
 * Environment variables:
 *   LOCATOR_BASE_URL   Base URL (default: http://localhost:8000)
 *   LOCATOR_TENANT     Tenant id header (default: demo)
 */

$baseUrl = getenv('LOCATOR_BASE_URL') ?: 'http://localhost:8000';
$tenant = getenv('LOCATOR_TENANT') ?: 'demo';

$fixturePath = __DIR__ . '/../tests/Locator/Fixture/address-golden.ndjson';

if (!is_file($fixturePath)) {
    fwrite(STDERR, "Fixture file not found: {$fixturePath}\n");
    exit(1);
}

fwrite(STDOUT, sprintf("Locator golden fixture run\nBase URL : %s\nTenant   : %s\nFile     : %s\n\n", $baseUrl, $tenant, $fixturePath));

$handle = fopen($fixturePath, 'rb');
if (!is_resource($handle)) {
    fwrite(STDERR, "Cannot open fixture file.\n");
    exit(1);
}

$index = 0;

while (($line = fgets($handle)) !== false) {
    $line = trim($line);
    if ($line === '') {
        continue;
    }

    $index++;
    $record = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

    $id = $record['id'] ?? (string)$index;
    $kind = $record['kind'] ?? 'unknown';
    $input = $record['input'] ?? [];

    $url = build_url($baseUrl, $kind, $input);

    fwrite(STDOUT, sprintf("[%02d] %s (%s) -> %s\n", $index, $id, $kind, $url));

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

    fwrite(STDOUT, sprintf("    %s (%.1f ms)\n", $statusLine, $durationMs));

    if ($body !== false && $body !== '') {
        $decoded = json_decode($body, true);
        if (is_array($decoded)) {
            if (isset($decoded['address'])) {
                fwrite(STDOUT, "    address: " . json_encode($decoded['address']) . "\n");
            }
            if (isset($decoded['items']) && is_array($decoded['items'])) {
                $count = count($decoded['items']);
                fwrite(STDOUT, "    items: {$count}\n");
                if ($count > 0 && isset($decoded['items'][0]['label'])) {
                    fwrite(STDOUT, "    first.label: " . $decoded['items'][0]['label'] . "\n");
                }
            }
            if (array_key_exists('quotaExceeded', $decoded)) {
                fwrite(STDOUT, "    quotaExceeded: " . var_export($decoded['quotaExceeded'], true) . "\n");
            }
        }
    }

    fwrite(STDOUT, "\n");
}

fclose($handle);

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

    if ($kind === 'reverse' || $kind === 'reverse_invalid') {
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
