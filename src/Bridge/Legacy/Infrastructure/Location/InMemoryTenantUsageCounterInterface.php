<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Infrastructure\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp.
 */
interface InMemoryTenantUsageCounterInterface
{
    public function increment(string $tenantId, string $operation, int $limitPerMinute): bool;
}
