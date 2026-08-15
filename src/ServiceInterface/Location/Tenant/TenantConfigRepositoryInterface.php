<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantConfigRepositoryInterface
{
    public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface;
}
