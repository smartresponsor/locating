<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface CostCapPolicyInterface
{
    public function allow(float $unitCost, float $cap): bool;
}
