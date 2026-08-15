<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Address\Location;

interface AddressQuotaGuardServiceInterface
{
    public function isAllowed(string $operation): bool;
}
