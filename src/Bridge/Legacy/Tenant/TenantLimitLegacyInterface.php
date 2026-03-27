<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Bridge\Legacy\Tenant\Location;

interface TenantLimitLegacyInterface
{
    public function tenantId(): string;

    public function operation(): string;

    public function limitPerMinute(): int;
}
