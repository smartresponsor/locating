<?php

declare(strict_types=1);

namespace App\Locating\PolicyInterface\Provider\Location\Runtime\Resilience;

interface HedgePolicyInterface
{
    /** @param list<float|int> $latencySample */
    public function delayMs(array $latencySample): int;
}
