<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\Http\HttpClient;
use App\Locating\Service\Location\Config\Env;
use App\Locating\ServiceInterface\Provider\Location\MapboxLocationProviderInterface;

class MapboxProviderService implements MapboxLocationProviderInterface
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
        return 'mapbox';
    }

    public function geocode(string $q, string $country): array
    {
        $token = $this->env->get('MAPBOX_TOKEN', '');
        if (!$token) {
            return [];
        }
        $url = 'https://api.mapbox.com/search/geocode/v6/forward?q='.rawurlencode($q).
               ($country ? '&country='.rawurlencode($country) : '').'&access_token='.$token.'&limit=5';
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        if (!is_array($data)) {
            return [];
        }
        $features = $data['features'] ?? [];
        if (!is_array($features)) {
            return [];
        }
        $out = [];
        foreach ($features as $f) {
            if (!is_array($f)) {
                continue;
            }
            $properties = is_array($f['properties'] ?? null) ? $f['properties'] : [];
            $geometry = is_array($f['geometry'] ?? null) ? $f['geometry'] : [];
            $coordinates = is_array($geometry['coordinates'] ?? null) ? $geometry['coordinates'] : [];
            $out[] = [
                'formatted' => is_string($properties['full_address'] ?? null) ? $properties['full_address'] : (is_string($f['place_name'] ?? null) ? $f['place_name'] : ''),
                'lat' => is_numeric($coordinates[1] ?? null) ? (float) $coordinates[1] : null,
                'lon' => is_numeric($coordinates[0] ?? null) ? (float) $coordinates[0] : null,
                'source' => 'mapbox',
                'confidence' => is_numeric($properties['accuracy'] ?? null) ? (float) $properties['accuracy'] : 0.5,
            ];
        }

        return $out;
    }

    public function reverse(float $lat, float $lon): array
    {
        $token = $this->env->get('MAPBOX_TOKEN', '');
        if (!$token) {
            return [];
        }
        $url = 'https://api.mapbox.com/search/geocode/v6/reverse?longitude='.$lon.'&latitude='.$lat.'&access_token='.$token;
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        if (!is_array($data)) {
            return [];
        }
        $features = $data['features'] ?? [];
        if (!is_array($features)) {
            return [];
        }
        $out = [];
        foreach ($features as $f) {
            if (!is_array($f)) {
                continue;
            }
            $properties = is_array($f['properties'] ?? null) ? $f['properties'] : [];
            $out[] = [
                'formatted' => is_string($properties['full_address'] ?? null) ? $properties['full_address'] : (is_string($f['place_name'] ?? null) ? $f['place_name'] : ''),
                'lat' => $lat,
                'lon' => $lon,
                'source' => 'mapbox',
                'confidence' => is_numeric($properties['accuracy'] ?? null) ? (float) $properties['accuracy'] : 0.5,
            ];
        }

        return $out;
    }

    public function autocomplete(string $q, string $country, string $bbox): array
    {
        $token = $this->env->get('MAPBOX_TOKEN', '');
        if (!$token) {
            return [];
        }
        $url = 'https://api.mapbox.com/search/searchbox/v1/suggest?limit=5&access_token='.$token.'&q='.rawurlencode($q);
        if ($country) {
            $url .= '&country='.rawurlencode($country);
        }
        if ($bbox) {
            $url .= '&bbox='.rawurlencode($bbox);
        }
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        if (!is_array($data)) {
            return [];
        }
        $suggestions = $data['suggestions'] ?? [];
        if (!is_array($suggestions)) {
            return [];
        }
        $out = [];
        foreach ($suggestions as $s) {
            if (!is_array($s)) {
                continue;
            }
            $coordinates = is_array($s['coordinates'] ?? null) ? $s['coordinates'] : [];
            $metadata = is_array($s['metadata'] ?? null) ? $s['metadata'] : [];
            $out[] = [
                'text' => is_string($s['nameEntity'] ?? null) ? $s['nameEntity'] : (is_string($s['full_address'] ?? null) ? $s['full_address'] : ''),
                'placeId' => is_string($s['mapbox_id'] ?? null) ? $s['mapbox_id'] : '',
                'lat' => is_numeric($coordinates['latitude'] ?? null) ? (float) $coordinates['latitude'] : null,
                'lon' => is_numeric($coordinates['longitude'] ?? null) ? (float) $coordinates['longitude'] : null,
                'score' => is_numeric($metadata['confidence'] ?? null) ? (float) $metadata['confidence'] : 0.5,
            ];
        }

        return $out;
    }
}
