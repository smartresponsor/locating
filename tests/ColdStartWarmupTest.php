<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\{ColdStartWarmup, ResultCache};
final class ColdStartWarmupTest {
    public function testRun(): void {
        $c = new ResultCache();
        $w = new ColdStartWarmup($c);
        $list = $w->plan('us','geocode',2);
        $n = $w->run('us','geocode',$list, fn($k)=>['k'=>$k], 120);
        assert($n===2);
    }
}
