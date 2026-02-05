<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\Infrastructure\Locator\Resilience;
use Smartresponsor\Infrastructure\Locator\Cache\RedisCache;

class CircuitBreaker {
    private RedisCache $cache; private string $name; private int $fail; private int $ttl;
    public function __construct(RedisCache $cache, string $name, int $fail, int $ttl){
        $this->cache=$cache; $this->name=$name; $this->fail=$fail; $this->ttl=$ttl;
    }
    public function allow(): bool {
        $state = $this->cache->get('cb:'.$this->name);
        return $state !== 'open';
    }
    public function recordSuccess(): void { $this->cache->set('cb:'.$this->name, 'closed', $this->ttl); }
    public function recordFailure(): void {
        $k='cbf:'.$this->name; $raw=$this->cache->get($k); $n=$raw?(int)$raw:0; $n++; 
        if($n >= $this->fail){ $this->cache->set('cb:'.$this->name, 'open', $this->ttl); $this->cache->set($k,'0',$this->ttl); }
        else { $this->cache->set($k,str($n),$this->ttl); }
    }
}
