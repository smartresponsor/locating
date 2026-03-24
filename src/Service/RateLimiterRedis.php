<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
/** Windowed counter limiter (per-key per-window) using RedisClientInterface */
final class RateLimiterRedis implements RateLimiterInterface {
    public function __construct(private RedisClientInterface $redis) {}
    public function allow(string $key, int $tokens=1, int $refillRate=10, int $burst=20): bool {
        $window = 1; // 1s window
        $rk = "rl:".$key.":".(int)floor(time()/$window);
        $val = $this->redis->incrby($rk, $tokens);
        if ($val === $tokens) { $this->redis->expire($rk, $window+1); }
        return $val <= $burst;
    }
}
