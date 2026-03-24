<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface TenantGuardInterface
{
    public function allow(TenantContextInterface $context, string $resourceTenant): bool;
}