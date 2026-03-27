<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface GeoFencePolicyLegacyInterface
{
    public function add(string $name, string $region, float $minLat, float $minLon, float $maxLat, float $maxLon): void;

    public function decide(float $lat, float $lon, string $defaultRegion = 'us'): string;

    public function allow(float $lat, float $lon, string $region): bool;
}
