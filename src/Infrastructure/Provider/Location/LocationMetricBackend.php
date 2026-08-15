<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\LocationMetricBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Metrics\LocationMetricRecorderInterface;

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
