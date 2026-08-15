<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface HedgePolicyInterface
{
    public function delayMs(array $latencySample): int;
}
