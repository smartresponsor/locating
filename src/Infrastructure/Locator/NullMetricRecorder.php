<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Infrastructure\Locator;

use Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface;

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
