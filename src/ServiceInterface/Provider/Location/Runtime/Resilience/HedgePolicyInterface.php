<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface HedgePolicyInterface
{
    /** @param list<float|int> $latencySample */
    public function delayMs(array $latencySample): int;
}
