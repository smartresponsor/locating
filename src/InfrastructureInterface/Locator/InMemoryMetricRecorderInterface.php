<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface InMemoryMetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;
    public function incrementCounter(string $operation, string $result): void;
    public function snapshot(): array;
}