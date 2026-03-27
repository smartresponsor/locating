<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\ServiceInterface;

interface TenantQuotaManagerInterface
{
    /** Check and optionally consume quota for operation. Return true if allowed. */
    public function allow(string $tenantId, string $op, int $unit = 1, bool $consume = true): bool;
    /** Return remaining quota snapshot for op. */
    public function remaining(string $tenantId, string $op): int;
}
