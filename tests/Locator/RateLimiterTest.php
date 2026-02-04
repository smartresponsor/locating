<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\RateLimiter;
final class RateLimiterTest {
    public function testAllow(): void {
        $l = new RateLimiter();
        $ok = 0; for($i=0;$i<25;$i++){ if($l->allow('k',1,100,10)) $ok++; }
        assert($ok >= 10 && $ok <= 25);
    }
}
