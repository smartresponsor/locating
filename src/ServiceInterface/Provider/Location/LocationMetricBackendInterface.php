<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface LocationMetricBackendInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;

    public function incrementCounter(string $operation, string $result): void;
}
