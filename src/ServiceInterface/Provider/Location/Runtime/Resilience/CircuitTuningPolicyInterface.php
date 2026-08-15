<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface CircuitTuningPolicyInterface
{
    public function window(float $errorRate, float $p95Ms): int;

    public function threshold(float $errorRate, float $p95Ms): int;
}
