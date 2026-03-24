<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\{CacheWarmer, ResultCache};
final class CacheWarmerTest {
    public function testWarm(): void {
        $c = new ResultCache();
        $w = new CacheWarmer($c);
        $w->addSeed('geocode','g:us:1', 10);
        $w->addSeed('geocode','g:us:2', 9);
        $plan = $w->plan('geocode', 2);
        $n = $w->warm('geocode', $plan, fn($k)=>['k'=>$k], 60);
        assert($n===2);
    }
}
