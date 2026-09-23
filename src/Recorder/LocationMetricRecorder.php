<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Recorder;

use App\Locating\RecorderInterface\LocationMetricRecorderInterface;
use App\Locating\ServiceInterface\Provider\Location\LocationMetricBackendInterface;

final class LocationMetricRecorder implements LocationMetricRecorderInterface
{
    public function __construct(private readonly LocationMetricBackendInterface $backend)
    {
    }

    public function recordLatency(string $operation, float $milliseconds): void
    {
        $this->backend->recordLatency($operation, $milliseconds);
    }

    public function incrementCounter(string $operation, string $result): void
    {
        $this->backend->incrementCounter($operation, $result);
    }
}
