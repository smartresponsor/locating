<?php

declare(strict_types=1);

namespace App\Locating\Provider\Location\Integration;

use App\Locating\Contract\Location\LocationProviderContract;

final class PhotonProvider implements LocationProviderContract
{
    public function __construct(private string $baseUrl)
    {
    }

    public function nameEntity(): string
    {
        return 'photon';
    }

    public function geocode(string $q): array
    {
        $url = rtrim($this->baseUrl, '/').'/api?q='.rawurlencode($q).'&limit=1';
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 4, CURLOPT_USERAGENT => 'locator-phase-41']);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($code >= 200 && $code < 300 && $body) {
            $decoded = json_decode((string) $body, true);
            if (!is_array($decoded)) {
                throw new \RuntimeException('geocode_invalid_json');
            }
            $features = $decoded['features'] ?? [];
            if (is_array($features) && is_array($features[0] ?? null)) {
                $feature = $features[0];
                $geometry = is_array($feature['geometry'] ?? null) ? $feature['geometry'] : [];
                $coordinates = is_array($geometry['coordinates'] ?? null) ? $geometry['coordinates'] : [];
                $properties = is_array($feature['properties'] ?? null) ? $feature['properties'] : [];
                if (is_numeric($coordinates[1] ?? null) && is_numeric($coordinates[0] ?? null)) {
                    return [
                        'lat' => (float) $coordinates[1],
                        'lon' => (float) $coordinates[0],
                        'formatted' => is_string($properties['nameEntity'] ?? null) ? $properties['nameEntity'] : $q,
                    ];
                }
            }
        }
        throw new \RuntimeException('geocode_failed');
    }
}
