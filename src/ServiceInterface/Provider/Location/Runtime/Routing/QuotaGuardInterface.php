<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface QuotaGuardInterface
{
    public function setLimit(string $tenantId, int $reqLimit, float $costLimit): void;

    public function charge(string $tenantId, float $costUnit = 0.0): bool;

    /** @return array{req:int, cost:float} */
    public function state(string $tenantId): array;
}
