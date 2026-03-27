<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface CostAwarePolicyLegacyInterface
{
    public function score(float $health, float $unitCost): float;
}
