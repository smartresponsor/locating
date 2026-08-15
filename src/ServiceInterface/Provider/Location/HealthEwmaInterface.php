<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface HealthEwmaInterface
{
    public function observe(float $latencyMs, bool $ok): float;

    public function health(float $latencyMs, float $errorRate): float;
}
