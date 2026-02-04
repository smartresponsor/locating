<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\ShadowTraffic;
final class ShadowTrafficTest {
    public function testPickRecord(): void {
        $s = new ShadowTraffic();
        $hit = 0;
        for($i=0;$i<200;$i++){
            $sh = $s->pick('a', ['a','b','c'], 0.5);
            if ($sh !== '') { $hit++; $s->record('a',$sh,['ok'=>1],['ok'=>1]); }
        }
        assert($hit > 0);
    }
}
