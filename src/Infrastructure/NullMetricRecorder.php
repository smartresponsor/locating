<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Infrastructure;

use App\InfrastructureInterface\MetricRecorderInterface;

/**
 * No-op metric recorder used when no real metric backend is configured.
 */
final class NullMetricRecorder implements MetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void
    {
        // Intentionally left blank.
    }

    public function incrementCounter(string $operation, string $result): void
    {
        // Intentionally left blank.
    }
}
