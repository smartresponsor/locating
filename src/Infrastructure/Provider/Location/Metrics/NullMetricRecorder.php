<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Infrastructure\Provider\Location\Metrics;

use App\Locating\InfrastructureInterface\Provider\Location\Metrics\LocationMetricRecorderInterface;

/**
 * No-op metric recorder used when no real metric backend is configured.
 */
final class NullMetricRecorder implements LocationMetricRecorderInterface
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
