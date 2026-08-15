<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\RateLimit;

interface TokenBucketInterface
{
    public function allow(): bool;
}
