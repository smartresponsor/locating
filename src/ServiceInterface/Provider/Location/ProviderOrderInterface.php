<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment\BanditPolicyInterface;

interface ProviderOrderInterface
{
    /**
     * @param array<string, array{latency_ms?:float|int,error_rate?:float|int,unit_cost?:float|int}> $signal
     * @return list<string>
     */
    public function rank(array $signal, BanditPolicyInterface $bandit): array;
}
