<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location\Runtime\Geo;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocationDistanceServiceInterface;

final class LocationDistanceService implements LocationDistanceServiceInterface
{
    public function meters(float $latitudeA, float $longitudeA, float $latitudeB, float $longitudeB): float
    {
        return Geohash::haversine($latitudeA, $longitudeA, $latitudeB, $longitudeB);
    }
}
