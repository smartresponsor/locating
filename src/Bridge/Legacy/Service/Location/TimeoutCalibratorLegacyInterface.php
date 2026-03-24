<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface TimeoutCalibratorLegacyInterface
{
    public function calibrate(int $baseMs, float $p95Ms, float $errorRate): int;
}
