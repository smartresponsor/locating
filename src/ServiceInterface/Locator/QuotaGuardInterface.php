<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface QuotaGuardInterface
{
    public function setLimit(string $tenantId, int $reqLimit, float $costLimit): void;
    public function charge(string $tenantId, float $costUnit=0.0): bool;
    public function state(string $tenantId): array;
}