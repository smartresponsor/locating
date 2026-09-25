<?php

declare(strict_types=1);

namespace App\Locating\PolicyInterface\Provider\Location\Runtime\Routing;

interface CostAwarePolicyInterface
{
    public function score(float $health, float $unitCost): float;
}
