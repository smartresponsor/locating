<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Infrastructure;

use Smartresponsor\InfrastructureInterface\MetricRecorderInterface;
use Smartresponsor\InfrastructureInterface\MetricSnapshotProviderInterface;

/**
 * In-memory metric recorder that can be used for development and smoke tests.
 * It collects basic aggregates for latency and error rate per operation.
 */
final class InMemoryMetricRecorder implements MetricRecorderInterface, MetricSnapshotProviderInterface
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

        if ($result === 'error') {
            $this->errorCountByOperation[$operation] = ($this->errorCountByOperation[$operation] ?? 0) + 1;
        }
    }

    public function snapshot(): array
    {
        $snapshot = [];

        $operationList = array_values(array_unique(array_merge(
            array_keys($this->countByOperation),
            array_keys($this->counterCountByOperation),
            array_keys($this->errorCountByOperation)
        )));

        foreach ($operationList as $operation) {
            $latencyCount = $this->countByOperation[$operation] ?? 0;
            $counterCount = $this->counterCountByOperation[$operation] ?? 0;
            $count = max($latencyCount, $counterCount);
            $sum = $this->latencySumByOperation[$operation] ?? 0.0;
            $errorCount = $this->errorCountByOperation[$operation] ?? 0;

            $avg = $count > 0 ? $sum / $count : 0.0;
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
