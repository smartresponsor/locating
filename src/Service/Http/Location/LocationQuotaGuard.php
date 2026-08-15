<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface;
use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardInterface;

final class LocationQuotaGuard implements LocationQuotaGuardInterface
{
    public function __construct(private readonly LocationQuotaGuardBackendInterface $backend)
    {
    }

    public function isAllowed(string $operation): bool
    {
        return match ($operation) {
            self::OPERATION_REVERSE => $this->backend->isAllowedReverse(),
            self::OPERATION_SUGGEST => $this->backend->isAllowedSuggest(),
            default => false,
        };
    }
}
