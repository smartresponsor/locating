<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Bridge\Legacy\Tenant\Location;

interface TenantConfigLegacyRepositoryInterface
{
    public function findLimit(string $tenantId, string $operation): ?TenantLimitLegacyInterface;
}
