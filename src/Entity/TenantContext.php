<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Entity;

use Smartresponsor\EntityInterface\TenantContextInterface;

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
