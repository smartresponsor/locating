<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\Integration\Provider\Location\Http\HttpClient;
use App\Locating\Service\Location\Config\Env;
use App\Locating\ServiceInterface\Provider\Location\HereLocationProviderInterface;

class HereProviderService implements HereLocationProviderInterface
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
        return 'here';
    }

    public function geocode(string $q, string $country): array
    {
        $key = $this->env->get('HERE_API_KEY', '');
        if (!$key) {
            return [];
        }
        $url = 'https://geocode.search.hereapi.com/v1/geocode?q='.rawurlencode($q).'&apiKey='.$key;
        if ($country) {
            $url .= '&in=countryCode:'.rawurlencode(strtoupper($country));
        }
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        if (!is_array($data)) {
            return [];
        }
        $items = $data['items'] ?? [];
        if (!is_array($items)) {
            return [];
        }
        $out = [];
        foreach ($items as $it) {
            if (!is_array($it)) {
                continue;
            }
            $position = is_array($it['position'] ?? null) ? $it['position'] : [];
            $address = is_array($it['address'] ?? null) ? $it['address'] : [];
            $scoring = is_array($it['scoring'] ?? null) ? $it['scoring'] : [];
            $out[] = [
                'formatted' => is_string($address['label'] ?? null) ? $address['label'] : '',
                'lat' => is_numeric($position['lat'] ?? null) ? (float) $position['lat'] : null,
                'lon' => is_numeric($position['lng'] ?? null) ? (float) $position['lng'] : null,
                'source' => 'here',
                'confidence' => is_numeric($scoring['queryScore'] ?? null) ? (float) $scoring['queryScore'] : 0.5,
            ];
        }

        return $out;
    }

    public function reverse(float $lat, float $lon): array
    {
        $key = $this->env->get('HERE_API_KEY', '');
        if (!$key) {
            return [];
        }
        $url = 'https://revgeocode.search.hereapi.com/v1/revgeocode?at='.$lat.','.$lon.'&apiKey='.$key;
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        if (!is_array($data)) {
            return [];
        }
        $items = $data['items'] ?? [];
        if (!is_array($items)) {
            return [];
        }
        $out = [];
        foreach ($items as $it) {
            if (!is_array($it)) {
                continue;
            }
            $address = is_array($it['address'] ?? null) ? $it['address'] : [];
            $scoring = is_array($it['scoring'] ?? null) ? $it['scoring'] : [];
            $out[] = [
                'formatted' => is_string($address['label'] ?? null) ? $address['label'] : '',
                'lat' => $lat,
                'lon' => $lon,
                'source' => 'here',
                'confidence' => is_numeric($scoring['queryScore'] ?? null) ? (float) $scoring['queryScore'] : 0.5,
            ];
        }

        return $out;
    }

    public function autocomplete(string $q, string $country, string $bbox): array
    {
        $key = $this->env->get('HERE_API_KEY', '');
        if (!$key) {
            return [];
        }
        $url = 'https://autocomplete.search.hereapi.com/v1/autocomplete?q='.rawurlencode($q).'&limit=5&apiKey='.$key;
        if ($country) {
            $url .= '&in=countryCode:'.rawurlencode(strtoupper($country));
        }
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        if (!is_array($data)) {
            return [];
        }
        $items = $data['items'] ?? [];
        if (!is_array($items)) {
            return [];
        }
        $out = [];
        foreach ($items as $it) {
            if (!is_array($it)) {
                continue;
            }
            $out[] = [
                'text' => is_string($it['title'] ?? null) ? $it['title'] : '',
                'placeId' => is_string($it['id'] ?? null) ? $it['id'] : '',
                'lat' => null,
                'lon' => null,
                'score' => is_numeric($it['score'] ?? null) ? (float) $it['score'] : 0.5,
            ];
        }

        return $out;
    }
}
