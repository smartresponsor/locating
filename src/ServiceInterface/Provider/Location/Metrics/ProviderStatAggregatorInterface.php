<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Metrics;

interface ProviderStatAggregatorInterface
{
    public function observe(string $providerId, string $region, float $latencyMs, bool $ok, float $cost): void;

    /** @return list<array{providerId:string,region:string,count:int,errorRate:float,p95Ms:float,costAvg:float}> */
    public function snapshot(): array;
}
