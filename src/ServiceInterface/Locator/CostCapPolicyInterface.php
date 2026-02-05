<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface CostCapPolicyInterface {
    /** Decide if single call with unit cost is allowed under per-call cap. */
    public function allow(float $unitCost, float $cap): bool;
}
