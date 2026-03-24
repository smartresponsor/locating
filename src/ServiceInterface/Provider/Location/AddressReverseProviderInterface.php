<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

use App\EntityInterface\Location\AddressReverseResultInterface;

interface AddressReverseProviderInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface;
}
