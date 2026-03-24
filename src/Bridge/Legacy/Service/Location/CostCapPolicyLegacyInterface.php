<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface CostCapPolicyLegacyInterface
{
    public function allow(float $unitCost, float $cap): bool;
}
