<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface CostAwarePolicyInterface {
    /** Return composite score where higher is better given health 0..1 and cost. */
    public function score(float $health, float $unitCost): float;
}
