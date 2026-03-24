<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Infrastructure;
final class CacheWarmer implements CacheWarmerInterface {
    /** @var array<string, array<string,float>> */
    private array $seed = [];
    public function __construct(private ResultCacheInterface $cache){}
    public function plan(string $op, int $limit): array {
        $m = $this->seed[$op] ?? [];
        arsort($m, \SORT_NUMERIC);
        return array_slice(array_keys($m), 0, max(1,$limit));
    }
    public function addSeed(string $op, string $key, float $weight): void {
        $w = $this->seed[$op] ?? [];
        $w[$key] = max(0.0,$weight);
        $this->seed[$op] = $w;
    }
    public function warm(string $op, array $key, callable $resolver, int $ttlS): int {
        $n=0;
        foreach ($key as $k){
            $k=(string)$k;
            if ($this->cache->get($k) !== null) { continue; }
            $val = $resolver($k);
            if (\is_array($val)) { $this->cache->put($k, $val, max(1,$ttlS)); $n++; }
        }
        return $n;
    }
}
