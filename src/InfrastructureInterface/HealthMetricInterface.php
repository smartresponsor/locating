<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface HealthMetricInterface
{
    public function serialize(string $provider, string $region, float $health, int $ts): array;
}