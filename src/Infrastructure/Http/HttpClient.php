<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace App\Infrastructure\Http;

class HttpClient
{
    public function get(string $url, array $headers = [], int $timeoutMs = 800): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT_MS => $timeoutMs,
            CURLOPT_HTTPHEADER => $this->formatHeaders($headers),
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($body === false) { throw new \RuntimeException("HTTP error: ".$err); }
        return [$status, $body];
    }

    public function postJson(string $url, array $payload, array $headers = [], int $timeoutMs = 800): array
    {
        $h = array_merge($headers, ['Content-Type' => 'application/json']);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT_MS => $timeoutMs,
            CURLOPT_HTTPHEADER => $this->formatHeaders($h),
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload, JSON_UNESCAPED_UNICODE),
        ]);
        $body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($body === false) { throw new \RuntimeException("HTTP error: ".$err); }
        return [$status, $body];
    }

    private function formatHeaders(array $headers): array
    {
        $out = [];
        foreach ($headers as $k => $v) { $out[] = $k . ': ' . $v; }
        return $out;
    }
}
