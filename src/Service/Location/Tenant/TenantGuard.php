<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Service\Location\Tenant;

use App\Locating\ServiceInterface\Location\Tenant\TenantContextInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantGuardInterface;

/**
 * Simple guard that ensures resource tenant matches current context.
 */
final class TenantGuard implements TenantGuardInterface
{
    public function allow(TenantContextInterface $context, string $resourceTenant): bool
    {
        return $context->id() === $resourceTenant;
    }
}
