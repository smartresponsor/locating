<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface AddressQuotaGuardLegacyServiceInterface
{
    public function isAllowed(string $operation): bool;
}
