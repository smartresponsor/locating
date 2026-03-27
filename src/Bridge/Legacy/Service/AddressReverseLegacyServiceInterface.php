<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

use App\Bridge\Legacy\Entity\Location\AddressResultLegacyInterface;

interface AddressReverseLegacyServiceInterface
{
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressResultLegacyInterface;
}
