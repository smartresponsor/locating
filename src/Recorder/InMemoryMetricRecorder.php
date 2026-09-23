<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Recorder;

use App\Locating\Contract\Location\MetricSnapshotProviderInterface;
use App\Locating\RecorderInterface\InMemoryMetricRecorderInterface;
use App\Locating\RecorderInterface\LocationMetricRecorderInterface;

/**
 * In-memory metric recorder that can be used for development and smoke tests.
 * It collects basic aggregates for latency and error rate per operation.
 */
final class InMemoryMetricRecorder implements InMemoryMetricRecorderInterface, LocationMetricRecorderInterface, MetricSnapshotProviderInterface
{
    /**
     * @var array<string,float>
     */
    private array $latencySumByOperation = [];

    /**
     * @var array<string,int>
     */
    private array $countByOperation = [];

    /**
     * @var array<string,int>
     */
    private array $counterCountByOperation = [];

    /**
     * @var array<string,int>
     */
    private array $errorCountByOperation = [];

    public function recordLatency(string $operation, float $milliseconds): void
    {
        if ($milliseconds < 0) {
            $milliseconds = 0.0;
        }

        $this->latencySumByOperation[$operation] = ($this->latencySumByOperation[$operation] ?? 0.0) + $milliseconds;
        $this->countByOperation[$operation] = ($this->countByOperation[$operation] ?? 0) + 1;
    }

    public function incrementCounter(string $operation, string $result): void
    {
        $this->counterCountByOperation[$operation] = ($this->counterCountByOperation[$operation] ?? 0) + 1;

        if ('error' === $result) {
            $this->errorCountByOperation[$operation] = ($this->errorCountByOperation[$operation] ?? 0) + 1;
        }
    }

    /** @return array<string,array{count:int,errorCount:int,avgMs:float,errorRate:float}> */
    public function snapshot(): array
    {
        $snapshot = [];

        $operationList = array_unique(array_merge(array_keys($this->countByOperation), array_keys($this->counterCountByOperation)));

        foreach ($operationList as $operation) {
            $latencyCount = $this->countByOperation[$operation] ?? 0;
            $count = $this->counterCountByOperation[$operation] ?? $latencyCount;
            $sum = $this->latencySumByOperation[$operation] ?? 0.0;
            $errorCount = $this->errorCountByOperation[$operation] ?? 0;

            $avg = $latencyCount > 0 ? $sum / $latencyCount : 0.0;
            $errorRate = $count > 0 ? $errorCount / $count : 0.0;

            $snapshot[$operation] = [
                'count' => $count,
                'errorCount' => $errorCount,
                'avgMs' => $avg,
                'errorRate' => $errorRate,
            ];
        }

        return $snapshot;
    }
}
