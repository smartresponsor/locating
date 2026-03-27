<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface TenantResourceQuotaLegacyInterface
{
    public function set(string $tenantId, string $resource, int $reqLimit, float $costLimit = 0.0): void;

    public function charge(string $tenantId, string $resource, float $costUnit = 0.0): bool;

    public function state(string $tenantId, string $resource): array;
}
