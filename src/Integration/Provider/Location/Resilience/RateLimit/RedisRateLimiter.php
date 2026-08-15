<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Resilience\RateLimit;

final class RedisRateLimiter implements RateLimiterInterface
{
    public function __construct(private \Redis $r)
    {
    }

    public function consume(string $bucket, int $perMinute): int
    {
        $key = 'rl:'.$bucket.':'.(int) floor(time() / 60);
        $count = (int) $this->r->incr($key);
        if (1 === $count) {
            $this->r->expire($key, 60);
        }
        if ($count > $perMinute) {
            $ttl = (int) $this->r->ttl($key);

            return $ttl < 0 ? 60 : $ttl;
        }

        return 0;
    }
}
