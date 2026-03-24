<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\TenantGuardLegacyInterface;
use App\Bridge\Legacy\Tenant\Location\TenantContextLegacyInterface;

/**
 * Simple guard that ensures resource tenant matches current context.
 */
final class TenantGuard implements TenantGuardLegacyInterface
{
    public function allow(TenantContextLegacyInterface $context, string $resourceTenant): bool
    {
        return $context->id() === $resourceTenant;
    }
}
