<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Http\Location;

use App\Locating\ModelInterface\Location\AddressReverseViewInterface;

interface LocationAddressReverseServiceInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseViewInterface;
}
