<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Http\Location;

use App\EntityInterface\Location\AddressReverseViewInterface;

interface AddressReverseServiceInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseViewInterface;
}
