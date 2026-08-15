<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Provider;

use App\Locating\Contract\Location\LocationProviderContract;
use App\Locating\Contract\Location\ReverseLocationProviderContract;

final class MockProvider implements LocationProviderContract, ReverseLocationProviderContract
{
    public function nameEntity(): string
    {
        return 'mock';
    }

    public function geocode(string $q): array
    {
        $q = trim(strtolower($q));
        $h = substr(sha1($q), 0, 8);
        $lat = (int) hexdec(substr($h, 0, 4)) % 90;
        $lon = (int) hexdec(substr($h, 4, 4)) % 180;

        return ['lat' => $lat + 0.1234, 'lon' => $lon + 0.5678, 'formatted' => ucwords($q)];
    }

    public function reverse(float $lat, float $lon): array
    {
        return ['street' => 'Main St', 'house' => (string) (((int) abs($lat * 10)) % 200 + 1), 'city' => 'Testville', 'region' => 'Test State', 'postalCode' => (string) (((int) abs($lon * 1000)) % 90000 + 10000), 'countryCode' => 'US', 'formatted' => 'Main St, Testville'];
    }
}
