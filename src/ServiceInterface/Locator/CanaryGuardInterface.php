<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface CanaryGuardInterface {
    /** Evaluate metrics and return decision: continue|pause|rollback. */
    public function decide(float $errorRate, float $p95Ms, float $budgetBurn): string;
}
