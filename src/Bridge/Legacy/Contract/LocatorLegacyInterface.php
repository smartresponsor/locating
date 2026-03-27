<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Contract\Location;

use Smartresponsor\Model\Locator\AddressData;
use Smartresponsor\Model\Locator\GeoPoint;

interface LocatorLegacyInterface
{
    public function normalize(string $raw): AddressData;

    public function geocode(AddressData $a): GeoPoint;

    public function reverse(GeoPoint $p): AddressData;
}
