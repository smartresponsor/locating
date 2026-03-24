<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Contract\Location;

interface RateLimiterLegacyInterface
{
    public function consume(string $bucket, int $perMinute): int;
}
