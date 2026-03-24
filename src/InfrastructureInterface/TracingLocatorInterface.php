<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface TracingLocatorInterface
{
    public function normalize(string $r): AddressData;
    public function geocode(AddressData $a): GeoPoint;
    public function reverse(GeoPoint $p): AddressData;
}