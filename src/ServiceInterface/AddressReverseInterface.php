<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

use App\EntityInterface\AddressResultInterface;

/**
 * High-level reverse geocoding service.
 */
interface AddressReverseInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressResultInterface;
}
