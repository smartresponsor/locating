<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantGuardInterface
{
    public function allow(TenantContextInterface $context, string $resourceTenant): bool;
}
