<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface TimeoutCalibratorInterface
{
    public function calibrate(int $baseMs, float $p95Ms, float $errorRate): int;
}
