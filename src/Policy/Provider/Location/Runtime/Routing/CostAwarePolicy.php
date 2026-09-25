<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Policy\Provider\Location\Runtime\Routing;

use App\Locating\PolicyInterface\Provider\Location\Runtime\Routing\CostAwarePolicyInterface;

final class CostAwarePolicy implements CostAwarePolicyInterface
{
    public function score(float $health, float $unitCost): float
    {
        $h = max(0.0, min(1.0, $health));
        $c = max(0.0001, $unitCost);

        return $h * (1.0 / (1.0 + $c)); // prefer lower cost and higher health
    }
}
