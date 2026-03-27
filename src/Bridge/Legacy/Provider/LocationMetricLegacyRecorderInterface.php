<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface LocationMetricLegacyRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;

    public function incrementCounter(string $operation, string $result): void;
}
