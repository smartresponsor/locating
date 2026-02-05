<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\EntityInterface\Locator;

interface TenantContextInterface
{
    public function id(): string;
}
