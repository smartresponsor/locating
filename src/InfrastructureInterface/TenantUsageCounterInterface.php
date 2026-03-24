<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\InfrastructureInterface;

interface TenantUsageCounterInterface
{
    /**
     * Increase usage for the given tenant and operation and return true if usage is still within limit.
     */
    public function increment(string $tenantId, string $operation, int $limitPerMinute): bool;
}
