<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\GeoPoint;

interface LocatorInterface
{
    public function normalize(string $raw): AddressData;

    public function geocode(AddressData $address): GeoPoint;

    public function reverse(GeoPoint $point): AddressData;
}
