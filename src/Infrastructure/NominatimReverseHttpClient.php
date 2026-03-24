<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Infrastructure;

use Smartresponsor\InfrastructureInterface\ReverseHttpClientInterface;

/**
 * Simple reverse geocoding client backed by OpenStreetMap Nominatim API.
 *
 * This client is intentionally minimal and can be replaced or decorated
 * in a real deployment. It is good enough for demos and small workloads.
 */
final class NominatimReverseHttpClient implements ReverseHttpClientInterface
{
    public function __construct(
        private string $baseUrl = 'https://nominatim.openstreetmap.org',
        private ?string $email = null,
        private int $timeoutSeconds = 5
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
    {
        $query = [
            'format' => 'jsonv2',
            'lat' => (string)$latitude,
            'lon' => (string)$longitude,
            'addressdetails' => '1',
        ];

        if ($countryCode !== null && $countryCode !== '') {
            $query['countrycodes'] = strtolower($countryCode);
        }

        if ($this->email !== null && $this->email !== '') {
            $query['email'] = $this->email;
        }

        $url = rtrim($this->baseUrl, '/') . '/reverse?' . http_build_query($query);

        $handle = curl_init($url);
        if ($handle === false) {
            throw new \RuntimeException('Failed to initialize curl for Nominatim reverse request');
        }

        curl_setopt_array($handle, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => $this->timeoutSeconds,
            CURLOPT_TIMEOUT => $this->timeoutSeconds,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 3,
            CURLOPT_USERAGENT => 'smartresponsor-locator-reverse/1.0',
        ]);

        $body = curl_exec($handle);
        if ($body === false) {
            $error = curl_error($handle);
            curl_close($handle);
            throw new \RuntimeException('Nominatim reverse request failed: ' . $error);
        }

        $statusCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        curl_close($handle);

        if ($statusCode < 200 || $statusCode >= 300) {
            throw new \RuntimeException('Nominatim reverse request failed with HTTP ' . $statusCode);
        }

        /** @var mixed $decoded */
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            throw new \RuntimeException('Nominatim reverse response is not a JSON object');
        }

        return $decoded;
    }
}
