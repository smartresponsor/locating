<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\Http\HttpClient;
use App\Locating\Service\Location\Config\Env;
use App\Locating\ServiceInterface\Provider\Location\NominatimLocationProviderInterface;

class NominatimProviderService implements NominatimLocationProviderInterface
{
    private Env $env;
    private HttpClient $http;

    public function __construct(Env $env)
    {
        $this->env = $env;
        $this->http = new HttpClient();
    }

    public function nameEntity(): string
    {
        return 'nominatim';
    }

    public function geocode(string $q, string $country): array
    {
        $base = rtrim($this->env->get('NOMINATIM_URL', 'https://nominatim.openstreetmap.org'), '/');
        $url = $base.'/search?format=jsonv2&q='.rawurlencode($q).'&limit=5&addressdetails=1';
        if ($country) {
            $url .= '&countrycodes='.rawurlencode(strtolower($country));
        }
        [$code,$body] = $this->http->get($url, ['User-Agent' => 'Smartresponsor-Locator/1.0']);
        if (200 !== $code) {
            return [];
        }
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return [];
        }
        $out = [];
        foreach ($decoded as $item) {
            if (!is_array($item)) {
                continue;
            }
            $out[] = [
                'formatted' => is_string($item['display_name'] ?? null) ? $item['display_name'] : '',
                'lat' => is_numeric($item['lat'] ?? null) ? (float) $item['lat'] : null,
                'lon' => is_numeric($item['lon'] ?? null) ? (float) $item['lon'] : null,
                'source' => 'nominatim',
                'confidence' => is_numeric($item['importance'] ?? null) ? (float) $item['importance'] : 0.4,
            ];
        }

        return $out;
    }

    public function reverse(float $lat, float $lon): array
    {
        $base = rtrim($this->env->get('NOMINATIM_URL', 'https://nominatim.openstreetmap.org'), '/');
        $url = $base.'/reverse?format=jsonv2&lat='.$lat.'&lon='.$lon;
        [$code,$body] = $this->http->get($url, ['User-Agent' => 'Smartresponsor-Locator/1.0']);
        if (200 !== $code) {
            return [];
        }
        $decoded = json_decode($body, true);
        if (!is_array($decoded)) {
            return [];
        }

        return [[
            'formatted' => is_string($decoded['display_name'] ?? null) ? $decoded['display_name'] : '',
            'lat' => $lat,
            'lon' => $lon,
            'source' => 'nominatim',
            'confidence' => 0.5,
        ]];
    }

    public function autocomplete(string $q, string $country, string $bbox): array
    {
        $res = $this->geocode($q, $country);
        $out = [];
        foreach ($res as $row) {
            $formatted = is_string($row['formatted'] ?? null) ? $row['formatted'] : '';
            $lat = is_numeric($row['lat'] ?? null) ? (float) $row['lat'] : null;
            $lon = is_numeric($row['lon'] ?? null) ? (float) $row['lon'] : null;
            $score = is_numeric($row['confidence'] ?? null) ? (float) $row['confidence'] : 0.5;
            $out[] = [
                'text' => $formatted,
                'placeId' => md5($formatted.'|'.($lat ?? '').'|'.($lon ?? '')),
                'lat' => $lat,
                'lon' => $lon,
                'score' => $score,
            ];
        }

        return $out;
    }
}
