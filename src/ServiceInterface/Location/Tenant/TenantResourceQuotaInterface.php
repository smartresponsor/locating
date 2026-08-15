<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantResourceQuotaInterface
{
    public function set(string $tenantId, string $resource, int $requestLimit, float $costLimit = 0.0): void;

    public function charge(string $tenantId, string $resource, float $costUnit = 0.0): bool;

    /** @return array<string, mixed> */
    public function state(string $tenantId, string $resource): array;
}
