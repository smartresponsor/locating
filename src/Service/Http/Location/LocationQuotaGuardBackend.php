<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\Service\Address\Location\AddressQuotaGuard as InnerAddressQuotaGuard;
use App\Locating\ServiceInterface\Address\Location\AddressQuotaGuardServiceInterface as InnerAddressQuotaGuardInterface;
use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface;

final class LocationQuotaGuardBackend implements LocationQuotaGuardBackendInterface
{
    public function __construct(private readonly InnerAddressQuotaGuardInterface $inner)
    {
    }

    public function isAllowedSuggest(): bool
    {
        return $this->inner->isAllowed(InnerAddressQuotaGuard::OPERATION_SUGGEST);
    }

    public function isAllowedReverse(): bool
    {
        return $this->inner->isAllowed(InnerAddressQuotaGuard::OPERATION_GEOCODE);
    }
}
