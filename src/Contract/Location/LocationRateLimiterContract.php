<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface LocationRateLimiterContract
{
    public function consume(string $bucket, int $perMinute): int;
}
