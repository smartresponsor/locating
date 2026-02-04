<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

use App\EntityInterface\Locator\AddressResultInterface;

/**
 * High-level reverse geocoding service.
 */
interface AddressReverseInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressResultInterface;
}
