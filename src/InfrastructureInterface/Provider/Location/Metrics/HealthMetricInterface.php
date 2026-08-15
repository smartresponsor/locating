<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Metrics;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp.
 */
interface HealthMetricInterface
{
    public function serialize(string $provider, string $region, float $health, int $ts): array;
}
