<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace App\Infrastructure\Resilience;
use App\Infrastructure\Cache\RedisCache;
class ProviderBudget {
    private RedisCache $cache; private int $perMin;
    public function __construct(RedisCache $cache, int $perMin){ $this->cache=$cache; $this->perMin=$perMin; }
    public function allow(string $name): bool {
        $k='pb:'.$name.':'.date('YmdHi'); $raw=$this->cache->get($k); $n=$raw?(int)$raw:0;
        if($n >= $this->perMin) return false;
        $this->cache->set($k, str($n+1), 70); return true;
    }
}
