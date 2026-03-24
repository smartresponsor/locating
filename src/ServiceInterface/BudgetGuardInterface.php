<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface BudgetGuardInterface {
    /** Set hard daily cap for tenant/op (float currency units). */
    public function setCap(string $tenantId, string $op, float $cap): void;
    /** Return cap and used tuple. */
    public function stat(string $tenantId, string $op): array;
    /** Return true if cost can be spent within cap. */
    public function canSpend(string $tenantId, string $op, float $cost): bool;
    /** Charge cost if within cap; return true on success. */
    public function charge(string $tenantId, string $op, float $cost): bool;
}
