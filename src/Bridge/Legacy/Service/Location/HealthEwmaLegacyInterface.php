<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface HealthEwmaLegacyInterface
{
    public function observe(float $latencyMs, bool $ok): float;

    public function health(float $latencyMs, float $errorRate): float;
}
