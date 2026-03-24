<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface CostAwarenessInterface
{
    public function score(float $latencyMs, float $health, float $unitCost, float $budgetLeft, float $costBias=0.5): float;
}