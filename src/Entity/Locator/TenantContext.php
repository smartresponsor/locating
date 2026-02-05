<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Entity\Locator;

use Smartresponsor\EntityInterface\Locator\TenantContextInterface;

final class TenantContext implements TenantContextInterface
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
