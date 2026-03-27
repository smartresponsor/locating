<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Provider\Location;

interface LocationMetricBackendInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;

    public function incrementCounter(string $operation, string $result): void;
}
