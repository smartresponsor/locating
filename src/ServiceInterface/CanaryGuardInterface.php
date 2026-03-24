<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface CanaryGuardInterface {
    /** Evaluate metrics and return decision: continue|pause|rollback. */
    public function decide(float $errorRate, float $p95Ms, float $budgetBurn): string;
}
