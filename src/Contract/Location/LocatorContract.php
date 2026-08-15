<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;

interface LocatorContract
{
    public function normalize(string $raw): AddressData;

    public function geocode(AddressData $a): GeoPoint;

    public function reverse(GeoPoint $p): AddressData;
}
