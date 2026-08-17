<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface AdaptiveProviderOrderInterface
{
    /**
     * @param array<string, array{p95Ms?:float|int,errorRate?:float|int,costAvg?:float|int}> $signal
     * @return list<string>
     */
    public function rank(array $signal): array;
}
