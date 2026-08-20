<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Geo;

interface LocationDistanceServiceInterface
{
    public function meters(float $latitudeA, float $longitudeA, float $latitudeB, float $longitudeB): float;
}
