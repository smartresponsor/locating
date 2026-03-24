<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Infrastructure\Locator;

use App\Bridge\Legacy\Infrastructure\Location\CacheInvalidatorInterface;
use App\Bridge\Legacy\Infrastructure\Location\ResultCacheInterface;

final class CacheInvalidator implements CacheInvalidatorInterface
{
    /** @var array<int,string> */
    private array $idx = [];

    public function __construct(private ResultCacheInterface $cache)
    {
    }

    public function index(string $key): void
    {
        $this->idx[$this->hash($key)] = $key;
    }

    public function key(string $key): int
    {
        $this->cache->invalidate($key);

        return 1;
    }

    public function prefix(string $prefix): int
    {
        $n = 0;
        $p = (string) $prefix;
        foreach ($this->idx as $k) {
            if (str_starts_with($k, $p)) {
                $this->cache->invalidate($k);
                ++$n;
            }
        }

        return $n;
    }

    private function hash(string $k): int
    {
        return crc32($k);
    }
}
