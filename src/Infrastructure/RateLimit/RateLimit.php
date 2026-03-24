<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace App\Infrastructure\RateLimit;
use App\Infrastructure\Cache\RedisCache;
class RateLimit {
    private RedisCache $cache; private int $rpm;
    public function __construct(RedisCache $cache, int $rpm){ $this->cache=$cache; $this->rpm=$rpm; }
    public function allow(string $bucket): bool {
        if($this->rpm<=0) return true;
        $key = 'rl:' . $bucket . ':' . date('YmdHi');
        $raw = $this->cache->get($key);
        $cnt = $raw ? (int)$raw : 0;
        if($cnt >= $this->rpm) return false;
        $this->cache->set($key, (string)($cnt+1), 70);
        return true;
    }
}
