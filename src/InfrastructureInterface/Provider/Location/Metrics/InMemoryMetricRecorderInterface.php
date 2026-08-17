<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Metrics;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface InMemoryMetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;
    public function incrementCounter(string $operation, string $result): void;
    /** @return array<string,array{count:int,errorCount:int,avgMs:float,errorRate:float}> */
    public function snapshot(): array;
}
