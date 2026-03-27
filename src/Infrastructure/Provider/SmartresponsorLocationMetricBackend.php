<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\LocationMetricLegacyRecorderInterface;
use App\InfrastructureInterface\Provider\Location\LocationMetricBackendInterface;

final class SmartresponsorLocationMetricBackend implements LocationMetricBackendInterface
{
    public function __construct(private readonly LocationMetricLegacyRecorderInterface $metricRecorder)
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
