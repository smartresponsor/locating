<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface HealthMonitorInterface
{
    public function update(string $provider, bool $ok, float $ms): void;
    public function snapshot(): array;
}