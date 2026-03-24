<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressResultInterface;

/**
 * High-level reverse geocoding service.
 */
interface AddressReverseInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressResultInterface;
}
