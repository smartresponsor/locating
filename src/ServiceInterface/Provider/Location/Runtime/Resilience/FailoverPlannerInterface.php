<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface FailoverPlannerInterface
{
    public function plan(string $region, array $provider, array $signal): array;
}
