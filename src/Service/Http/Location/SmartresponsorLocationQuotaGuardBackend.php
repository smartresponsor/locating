<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Http\Location;

use App\Bridge\Legacy\Service\Location\AddressQuotaGuardLegacyServiceInterface as InnerAddressQuotaGuardInterface;
use App\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface;
use Smartresponsor\Service\Locator\AddressQuotaGuard as InnerAddressQuotaGuard;

final class SmartresponsorLocationQuotaGuardBackend implements LocationQuotaGuardBackendInterface
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
