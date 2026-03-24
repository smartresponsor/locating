<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

use Smartresponsor\EntityInterface\TenantContextInterface;

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
