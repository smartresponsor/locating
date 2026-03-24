<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\InfrastructureInterface;

interface MetricRecorderInterface
{
    public function recordLatency(string $operation, float $milliseconds): void;

    public function incrementCounter(string $operation, string $result): void;
}
