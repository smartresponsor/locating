<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface QuotaGuardLegacyInterface
{
    public function setLimit(string $tenantId, int $reqLimit, float $costLimit): void;

    public function charge(string $tenantId, float $costUnit = 0.0): bool;

    public function state(string $tenantId): array;
}
