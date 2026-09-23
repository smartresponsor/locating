<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\Service\Provider\Location\Resilience;

use App\Locating\Service\Provider\Location\Cache\RedisCache;

class ProviderBudget
{
    private RedisCache $cache;
    private int $perMin;
    public function __construct(RedisCache $cache, int $perMin)
    {
        $this->cache = $cache;
        $this->perMin = $perMin;
    }
    public function allow(string $nameEntity): bool
    {
        $k = 'pb:'.$nameEntity.':'.date('YmdHi');
        $raw = $this->cache->get($k);
        $n = is_numeric($raw) ? (int) $raw : 0;
        if ($n >= $this->perMin) {
            return false;
        }
        $this->cache->set($k, (string) ($n + 1), 70);
        return true;
    }
}
