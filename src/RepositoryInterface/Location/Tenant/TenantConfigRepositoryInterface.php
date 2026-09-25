<?php

declare(strict_types=1);

namespace App\Locating\RepositoryInterface\Location\Tenant;

use App\Locating\ServiceInterface\Location\Tenant\TenantLimitInterface;

interface TenantConfigRepositoryInterface
{
    public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface;
}
