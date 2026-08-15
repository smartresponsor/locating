<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Geo;

interface GeoFencePolicyInterface
{
    public function add(string $nameEntity, string $region, float $minLat, float $minLon, float $maxLat, float $maxLon): void;

    public function decide(float $lat, float $lon, string $defaultRegion = 'us'): string;

    public function allow(float $lat, float $lon, string $region): bool;
}
