<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\EntityInterface;

interface TenantLimitInterface
{
    public function tenantId(): string;

    public function operation(): string;

    public function limitPerMinute(): int;
}
