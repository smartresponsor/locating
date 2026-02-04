<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\EntityInterface\Locator;

interface TenantLimitInterface
{
    public function tenantId(): string;

    public function operation(): string;

    public function limitPerMinute(): int;
}
