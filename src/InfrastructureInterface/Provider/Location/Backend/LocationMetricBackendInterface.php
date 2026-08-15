<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Backend;

interface LocationMetricBackendInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;

    public function incrementCounter(string $operation, string $result): void;
}
