<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location;

use App\Locating\RecorderInterface\LocationMetricRecorderInterface;
use App\Locating\ServiceInterface\Provider\Location\LocationMetricBackendInterface;

final class LocationMetricBackend implements LocationMetricBackendInterface
{
    public function __construct(private readonly LocationMetricRecorderInterface $metricRecorder)
    {
    }

    public function recordLatency(string $operation, float $milliseconds): void
    {
        $this->metricRecorder->recordLatency($operation, $milliseconds);
    }

    public function incrementCounter(string $operation, string $result): void
    {
        $this->metricRecorder->incrementCounter($operation, $result);
    }
}
