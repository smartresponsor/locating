<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface HedgePolicyLegacyInterface
{
    public function delayMs(array $latencySample): int;
}
