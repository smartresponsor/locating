<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Provider;

use App\Locating\Contract\Location\LocationProviderContract;
use App\Locating\Contract\Location\ReverseLocationProviderContract;

final class NominatimProvider implements LocationProviderContract, ReverseLocationProviderContract
{
    public function __construct(private string $baseUrl)
    {
    }

    public function nameEntity(): string
    {
        return 'nominatim';
    }

    public function geocode(string $q): array
    {
        $url = rtrim($this->baseUrl, '/').'/search?format=json&limit=1&q='.rawurlencode($q);
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 4, CURLOPT_USERAGENT => 'locator-phase-38']);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($code >= 200 && $code < 300 && $body) {
            $arr = json_decode((string) $body, true);
            if (is_array($arr) && is_array($arr[0] ?? null)) {
                $first = $arr[0];
                $latitude = $first['lat'] ?? null;
                $longitude = $first['lon'] ?? null;
                if (is_numeric($latitude) && is_numeric($longitude)) {
                    return [
                        'lat' => (float) $latitude,
                        'lon' => (float) $longitude,
                        'formatted' => is_string($first['display_name'] ?? null) ? $first['display_name'] : $q,
                    ];
                }
            }
        }
        throw new \RuntimeException('geocode_failed');
    }

    public function reverse(float $lat, float $lon): array
    {
        $url = rtrim($this->baseUrl, '/').'/reverse?format=json&lat='.rawurlencode((string) $lat).'&lon='.rawurlencode((string) $lon);
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 4, CURLOPT_USERAGENT => 'locator-phase-38']);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($code >= 200 && $code < 300 && $body) {
            $decoded = json_decode((string) $body, true);
            if (!is_array($decoded)) {
                throw new \RuntimeException('reverse_invalid_json');
            }
            $address = is_array($decoded['address'] ?? null) ? $decoded['address'] : [];
            $street = $address['road'] ?? $address['pedestrian'] ?? $address['footway'] ?? '';
            $city = $address['city'] ?? $address['town'] ?? $address['village'] ?? '';

            return [
                'street' => is_string($street) ? $street : '',
                'house' => is_string($address['house_number'] ?? null) ? $address['house_number'] : '',
                'city' => is_string($city) ? $city : '',
                'region' => is_string($address['state'] ?? null) ? $address['state'] : '',
                'postalCode' => is_string($address['postcode'] ?? null) ? $address['postcode'] : '',
                'countryCode' => is_string($address['country_code'] ?? null) ? strtoupper($address['country_code']) : '',
                'formatted' => is_string($decoded['display_name'] ?? null) ? $decoded['display_name'] : '',
            ];
        }
        throw new \RuntimeException('reverse_failed');
    }
}
