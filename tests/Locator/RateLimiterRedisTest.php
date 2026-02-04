<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\{FakeRedis, RateLimiterRedis};
final class RateLimiterRedisTest {
    public function testWindowBurst(): void {
        $r = new RateLimiterRedis(new FakeRedis());
        $ok=0; for($i=0;$i<25;$i++){ if($r->allow('k',1,0,10)) $ok++; }
        assert($ok <= 10);
    }
}
