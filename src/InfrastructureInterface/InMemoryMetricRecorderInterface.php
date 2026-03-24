<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\InfrastructureInterface;

/**
 */

interface InMemoryMetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;
    public function incrementCounter(string $operation, string $result): void;
    public function snapshot(): array;
}