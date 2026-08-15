<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\InfrastructureInterface\Location\Tenant;

interface TenantUsageCounterInterface
{
    /**
     * Increase usage for the given tenant and operation and return true if usage is still within limit.
     */
    public function increment(string $tenantId, string $operation, int $limitPerMinute): bool;
}
