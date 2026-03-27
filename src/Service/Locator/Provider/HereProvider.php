<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace Smartresponsor\Service\Locator\Provider;

use App\Bridge\Legacy\Service\Location\HereProviderLegacyInterface;
use Smartresponsor\Infrastructure\Locator\Http\HttpClient;
use Smartresponsor\Service\Locator\Config\Env;

class HereProvider implements HereProviderLegacyInterface
{
    private Env $env;
    private HttpClient $http;

    public function __construct(Env $env)
    {
        $this->env = $env;
        $this->http = new HttpClient();
    }

    public function name(): string
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
        $out = [];
        foreach (($data['items'] ?? []) as $it) {
            $pos = $it['position'] ?? ['lat' => null, 'lng' => null];
            $out[] = [
                'formatted' => $it['address']['label'] ?? '',
                'lat' => $pos['lat'], 'lon' => $pos['lng'],
                'source' => 'here', 'confidence' => $it['scoring']['queryScore'] ?? 0.5,
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
        $out = [];
        foreach (($data['items'] ?? []) as $it) {
            $out[] = [
                'formatted' => $it['address']['label'] ?? '',
                'lat' => $lat, 'lon' => $lon,
                'source' => 'here', 'confidence' => $it['scoring']['queryScore'] ?? 0.5,
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
        $url = 'https://autocomplete.search.hereapi.com/v1/autocomplete?q='.rawurlencode($q) + '&limit=5' + '&apiKey=' + $key;
        if ($country) {
            $url .= '&in=countryCode:'.rawurlencode(strtoupper($country));
        }
        [$code, $body] = $this->http->get($url);
        if (200 !== $code) {
            return [];
        }
        $data = json_decode($body, true);
        $out = [];
        foreach (($data['items'] ?? []) as $it) {
            $out[] = [
                'text' => $it['title'] ?? '',
                'placeId' => $it['id'] ?? '',
                'lat' => null, 'lon' => null,
                'score' => $it['score'] ?? 0.5,
            ];
        }

        return $out;
    }
}
