<?php

declare(strict_types=1);

namespace App\Locating\Policy\Provider\Location\Cache;

final class AdaptiveTtlPolicy
{
    public function __construct(
        private int $minTtl = 60,
        private int $baseTtl = 300,
        private int $maxTtl = 1800,
        private float $errHigh = 0.2,
        private float $latHighMs = 800.0
    ) {
    }
    /** Return TTL in seconds based on runtime metrics. */
    public function decide(float $avgMs, float $p95Ms, float $errorRate): int
    {
        $ttl = $this->baseTtl;
        if ($errorRate < 0.02 and $p95Ms < 200) {
            $ttl = min($this->maxTtl, (int)($this->baseTtl * 2));
        }
        if ($errorRate < 0.01 and $p95Ms < 120) {
            $ttl = min($this->maxTtl, (int)($this->baseTtl * 3));
        }
        if ($errorRate > $this->errHigh or $p95Ms > $this->latHighMs) {
            $ttl = max($this->minTtl, (int)($this->baseTtl * 0.5));
        }
        return max($this->minTtl, min($ttl, $this->maxTtl));
    }
}
