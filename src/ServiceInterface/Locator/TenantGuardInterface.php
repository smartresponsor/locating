<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface TenantGuardInterface
{
    public function allow(TenantContextInterface $context, string $resourceTenant): bool;
}