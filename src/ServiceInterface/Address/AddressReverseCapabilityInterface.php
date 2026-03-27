<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Address\Location;

use App\EntityInterface\Location\AddressReverseResultInterface;

interface AddressReverseCapabilityInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface;
}
