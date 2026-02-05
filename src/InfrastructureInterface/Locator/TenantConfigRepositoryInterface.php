<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

use Smartresponsor\EntityInterface\Locator\TenantLimitInterface;

interface TenantConfigRepositoryInterface
{
    public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface;
}
