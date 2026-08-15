<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Geo;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;

interface LocatorInterface
{
    public function normalize(string $raw): AddressData;
    public function geocode(AddressData $a): GeoPoint;
    public function reverse(GeoPoint $p): AddressData;
}
