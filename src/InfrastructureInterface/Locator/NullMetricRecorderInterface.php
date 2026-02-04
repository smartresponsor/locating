<?php
declare(strict_types=1);

namespace App\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface NullMetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;
    public function incrementCounter(string $operation, string $result): void;
}