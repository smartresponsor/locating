<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\EntityInterface\Locator\TenantContextInterface;

/**
 * Simple guard that ensures resource tenant matches current context.
 */
final class TenantGuard
{
    public function allow(TenantContextInterface $context, string $resourceTenant): bool
    {
        return $context->id() === $resourceTenant;
    }
}
