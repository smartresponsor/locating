<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\Infrastructure\Provider\Location\Resilience;

use App\Locating\Service\Provider\Location\Cache\RedisCache;

class CircuitBreaker
{
    private RedisCache $cache;
    private string $nameEntity;
    private int $fail;
    private int $ttl;
    public function __construct(RedisCache $cache, string $nameEntity, int $fail, int $ttl)
    {
        $this->cache = $cache;
        $this->nameEntity = $nameEntity;
        $this->fail = $fail;
        $this->ttl = $ttl;
    }
    public function allow(): bool
    {
        $state = $this->cache->get('cb:'.$this->nameEntity);
        return $state !== 'open';
    }
    public function recordSuccess(): void
    {
        $this->cache->set('cb:'.$this->nameEntity, 'closed', $this->ttl);
    }
    public function recordFailure(): void
    {
        $k = 'cbf:'.$this->nameEntity;
        $raw = $this->cache->get($k);
        $n = $raw ? (int)$raw : 0;
        $n++;
        if ($n >= $this->fail) {
            $this->cache->set('cb:'.$this->nameEntity, 'open', $this->ttl);
            $this->cache->set($k, '0', $this->ttl);
        } else {
            $this->cache->set($k, (string) $n, $this->ttl);
        }
    }
}
