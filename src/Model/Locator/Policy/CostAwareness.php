<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Model\Locator\Policy;

final class CostAwareness
{
    public function score(float $latencyMs, float $health, float $unitCost, float $budgetLeft, float $costBias = 0.5): float
    {
        $lat = max(1.0, $latencyMs);
        $h   = max(0.01, min(1.0, $health));
        $cost = max(0.0001, $unitCost);
        $budgetFactor = $budgetLeft <= 0 ? 0.5 : min(1.5, 1.0 + $budgetLeft);
        return (1.0 / $lat) * $h * $budgetFactor * (1.0 / (1.0 + $costBias * $cost));
    }
}
