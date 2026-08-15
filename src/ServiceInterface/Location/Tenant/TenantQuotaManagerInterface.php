<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantQuotaManagerInterface
{
    public function allow(string $tenantId, string $op, int $unit = 1, bool $consume = true): bool;

    public function remaining(string $tenantId, string $op): int;
}
