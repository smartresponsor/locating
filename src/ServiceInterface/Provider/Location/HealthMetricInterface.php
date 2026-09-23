<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp.
 */
interface HealthMetricInterface
{
    /** @return array{provider:string,region:string,health:float,ts:int} */
    public function serialize(string $provider, string $region, float $health, int $ts): array;
}
