#!/usr/bin/env php
<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

$baseUrl = getenv('LOCATOR_BASE_URL') ?: 'http://localhost:8000';

function requestUrl(string $url): array
{
    $ch = curl_init($url);
    if ($ch === false) {
        throw new RuntimeException('Unable to initialize cURL');
    }

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);

    $response = curl_exec($ch);
    if ($response === false) {
        $error = curl_error($ch);
        curl_close($ch);
        throw new RuntimeException('cURL error: ' . $error);
    }

    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    $header = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);

    return [
        'statusCode' => $statusCode,
        'header' => $header,
        'body' => $body,
    ];
}

function printTitle(string $title): void
{
    fwrite(STDOUT, PHP_EOL . '=== ' . $title . ' ===' . PHP_EOL);
}

function printJsonPreview(string $body, int $maxLength = 320): void
{
    $trimmed = trim($body);
    if ($trimmed === '') {
        fwrite(STDOUT, "(empty body)" . PHP_EOL);
        return;
    }

    $decoded = json_decode($trimmed, true);
    if (is_array($decoded)) {
        $pretty = json_encode($decoded, JSON_PRETTY_PRINT);
        if ($pretty === false) {
            fwrite(STDOUT, substr($trimmed, 0, $maxLength) . PHP_EOL);
            return;
        }

        if (strlen($pretty) > $maxLength) {
            $pretty = substr($pretty, 0, $maxLength) . PHP_EOL . '...';
        }

        fwrite(STDOUT, $pretty . PHP_EOL);
        return;
    }

    if (strlen($trimmed) > $maxLength) {
        $trimmed = substr($trimmed, 0, $maxLength) . PHP_EOL . '...';
    }

    fwrite(STDOUT, $trimmed . PHP_EOL);
}

fwrite(STDOUT, 'Locator demo run' . PHP_EOL);
fwrite(STDOUT, 'Base URL: ' . $baseUrl . PHP_EOL);

try {
    // Status
    printTitle('Status');
    $statusUrl = rtrim($baseUrl, '/') . '/locator/status';
    $statusResult = requestUrl($statusUrl);
    fwrite(STDOUT, 'HTTP ' . $statusResult['statusCode'] . PHP_EOL);
    printJsonPreview($statusResult['body']);

    // Suggest
    printTitle('Suggest');
    $suggestUrl = rtrim($baseUrl, '/') . '/locator/address/suggest?query=' . urlencode('123 Main St') . '&country=US&limit=5';
    $suggestResult = requestUrl($suggestUrl);
    fwrite(STDOUT, 'HTTP ' . $suggestResult['statusCode'] . PHP_EOL);
    printJsonPreview($suggestResult['body']);

    // Metrics
    printTitle('Metrics');
    $metricUrl = rtrim($baseUrl, '/') . '/locator/metrics';
    $metricResult = requestUrl($metricUrl);
    fwrite(STDOUT, 'HTTP ' . $metricResult['statusCode'] . PHP_EOL);

    $body = trim($metricResult['body']);
    $lines = explode("\n", $body);
    $previewLines = array_slice($lines, 0, 10);
    foreach ($previewLines as $line) {
        fwrite(STDOUT, $line . PHP_EOL);
    }
    if (count($lines) > count($previewLines)) {
        fwrite(STDOUT, '...' . PHP_EOL);
    }

    fwrite(STDOUT, PHP_EOL . 'Demo run finished.' . PHP_EOL);
    exit(0);
} catch (Throwable $exception) {
    fwrite(STDERR, 'Error: ' . $exception->getMessage() . PHP_EOL);
    exit(1);
}
