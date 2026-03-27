<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

use App\Bridge\Legacy\Tenant\Location\TenantContextLegacyInterface;

interface TenantGuardLegacyInterface
{
    public function allow(TenantContextLegacyInterface $context, string $resourceTenant): bool;
}
