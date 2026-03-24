<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface InMemoryTenantUsageCounterInterface
{
    public function increment(string $tenantId, string $operation, int $limitPerMinute): bool;
}