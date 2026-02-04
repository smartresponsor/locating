<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface CostAwarenessInterface
{
    public function score(float $latencyMs, float $health, float $unitCost, float $budgetLeft, float $costBias=0.5): float;
}