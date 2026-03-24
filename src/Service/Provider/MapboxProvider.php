<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\Service\Provider;

use Smartresponsor\Domain\Config\Env;
use Smartresponsor\Infrastructure\Http\HttpClient;
use Smartresponsor\ServiceInterface\Provider\GeocodeProviderInterface;

class MapboxProvider implements GeocodeProviderInterface
{
    private Env $env;
    private HttpClient $http;
    public function __construct(Env $env)
    {
        $this->env = $env;
        $this->http = new HttpClient();
    }
    public function name(): string { return 'mapbox'; }

    public function geocode(string $q, string $country): array
    {
        $token = $this->env->get('MAPBOX_TOKEN', '');
        if (!$token) return [];
        $url = 'https://api.mapbox.com/search/geocode/v6/forward?q=' . rawurlencode($q) .
               ($country ? '&country=' . rawurlencode($country) : '') . '&access_token=' . $token . '&limit=5';
        [$code, $body] = $this->http->get($url);
        if ($code !== 200) return [];
        $data = json_decode($body, true);
        $out = [];
        foreach (($data['features'] ?? []) as $f) {
            $out[] = [
                'formatted' => $f['properties']['full_address'] ?? ($f['place_name'] ?? ''),
                'lat' => $f['geometry']['coordinates'][1] ?? null,
                'lon' => $f['geometry']['coordinates'][0] ?? null,
                'source' => 'mapbox', 'confidence' => $f['properties']['accuracy'] ?? 0.5
            ];
        }
        return $out;
    }

    public function reverse(float $lat, float $lon): array
    {
        $token = $this->env->get('MAPBOX_TOKEN', '');
        if (!$token) return [];
        $url = 'https://api.mapbox.com/search/geocode/v6/reverse?longitude=' . $lon . '&latitude=' . $lat . '&access_token=' . $token;
        [$code, $body] = $this->http->get($url);
        if ($code !== 200) return [];
        $data = json_decode($body, true);
        $out = [];
        foreach (($data['features'] ?? []) as $f) {
            $out[] = [
                'formatted' => $f['properties']['full_address'] ?? ($f['place_name'] ?? ''),
                'lat' => $lat, 'lon' => $lon, 'source' => 'mapbox', 'confidence' => $f['properties']['accuracy'] ?? 0.5
            ];
        }
        return $out;
    }

    public function autocomplete(string $q, string $country, string $bbox): array
    {
        $token = $this->env->get('MAPBOX_TOKEN', '');
        if (!$token) return [];
        $url = 'https://api.mapbox.com/search/searchbox/v1/suggest?limit=5&access_token=' . $token . '&q=' . rawurlencode($q);
        if ($country) $url .= '&country=' . rawurlencode($country);
        if ($bbox) $url .= '&bbox=' . rawurlencode($bbox);
        [$code, $body] = $this->http->get($url);
        if ($code !== 200) return [];
        $data = json_decode($body, true);
        $out = [];
        foreach (($data['suggestions'] ?? []) as $s) {
            $out[] = [
                'text' => $s['name'] ?? $s['full_address'] ?? '',
                'placeId' => $s['mapbox_id'] ?? '',
                'lat' => $s['coordinates']['latitude'] ?? null,
                'lon' => $s['coordinates']['longitude'] ?? null,
                'score' => $s['metadata']['confidence'] ?? 0.5
            ];
        }
        return $out;
    }
}
