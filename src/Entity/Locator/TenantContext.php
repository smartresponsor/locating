<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Entity\Locator;

use App\Bridge\Legacy\Tenant\Location\TenantContextLegacyInterface;

final class TenantContext implements TenantContextLegacyInterface
{
    public function __construct(private string $id)
    {
    }

    public function id(): string
    {
        return $this->id;
    }
}
