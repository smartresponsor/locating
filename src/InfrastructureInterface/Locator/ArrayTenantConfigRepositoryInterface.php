<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface ArrayTenantConfigRepositoryInterface
{
    public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface;
}