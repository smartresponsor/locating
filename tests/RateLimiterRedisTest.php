<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\{FakeRedis, RateLimiterRedis};
final class RateLimiterRedisTest {
    public function testWindowBurst(): void {
        $r = new RateLimiterRedis(new FakeRedis());
        $ok=0; for($i=0;$i<25;$i++){ if($r->allow('k',1,0,10)) $ok++; }
        assert($ok <= 10);
    }
}
