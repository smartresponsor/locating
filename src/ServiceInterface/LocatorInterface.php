<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

use Smartresponsor\Entity\AddressData;
use Smartresponsor\Entity\GeoPoint;

interface LocatorInterface
{
    public function normalize(string $raw): AddressData;

    public function geocode(AddressData $address): GeoPoint;

    public function reverse(GeoPoint $point): AddressData;
}
