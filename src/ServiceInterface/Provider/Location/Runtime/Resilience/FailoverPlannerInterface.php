<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface FailoverPlannerInterface
{
    /**
     * @param list<string> $provider
     * @param array<string, array{p95_ms?:float|int,error_rate?:float|int}> $signal
     * @return list<string>
     */
    public function plan(string $region, array $provider, array $signal): array;
}
