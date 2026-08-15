<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Provider;

final class LocatorConfig
{
    /** @param list<string> $providers */
    public function __construct(
        public array $providers = ['osm://?priority=100','fallback://?priority=0'],
        public int $retries = 2,
        public int $retryBaseMs = 100,
        public int $cbThreshold = 5,
        public int $cbCooldownSec = 30,
        public int $minTtl = 60,
        public int $baseTtl = 300,
        public int $maxTtl = 1800,
        public int $negCacheTtl = 60,
        public int $cacheMax = 256,
        public int $metricsWindow = 256
    ) {
    }
}
