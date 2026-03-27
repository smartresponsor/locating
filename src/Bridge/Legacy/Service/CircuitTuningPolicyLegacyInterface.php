<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface CircuitTuningPolicyLegacyInterface
{
    public function window(float $errorRate, float $p95Ms): int;

    public function threshold(float $errorRate, float $p95Ms): int;
}
