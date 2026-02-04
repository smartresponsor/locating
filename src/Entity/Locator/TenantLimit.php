<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Entity\Locator;

use App\EntityInterface\Locator\TenantLimitInterface;

final class TenantLimit implements TenantLimitInterface
{
    public function __construct(
        private string $tenantId,
        private string $operation,
        private int $limitPerMinute
    ) {
    }

    public function tenantId(): string
    {
        return $this->tenantId;
    }

    public function operation(): string
    {
        return $this->operation;
    }

    public function limitPerMinute(): int
    {
        return $this->limitPerMinute;
    }
}
