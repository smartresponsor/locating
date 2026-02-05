<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface TenantQuotaInterface {
    /** Return adaptive daily limit for tenant/op (>=1). */
    public function limit(string $tenantId, string $op): int;
    /** Update usage and error, recalc adaptive limit, return new limit. */
    public function update(string $tenantId, string $op, int $used, float $errorRate): int;
    /** Bootstrap base limit. */
    public function setBase(string $tenantId, string $op, int $base): void;
}
