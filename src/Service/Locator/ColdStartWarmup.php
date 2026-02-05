<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class ColdStartWarmup implements ColdStartWarmupInterface {
    public function __construct(private ResultCacheInterface $cache) {}
    public function plan(string $region, string $op, int $limit): array {
        // naive seed set: popular keys by region/op from seed table (projection later)
        $seed = [];
        for ($i=0; $i<max(1,$limit); $i++) { $seed[] = $op.':'.$region.':seed:'.$i; }
        return $seed;
    }
    public function run(string $region, string $op, array $key, callable $resolver, int $ttlS=300): int {
        $n=0;
        foreach ($key as $k) {
            $k = (string)$k;
            if ($this->cache->get($k) !== null) { continue; }
            $val = $resolver($k);
            if (\is_array($val)) { $this->cache->put($k, $val, max(1,$ttlS)); $n++; }
        }
        return $n;
    }
}
