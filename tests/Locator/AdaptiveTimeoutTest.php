<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\AdaptiveTimeout;
final class AdaptiveTimeoutTest {
    public function testTimeoutIncreasesWithLatency(): void {
        $a = new AdaptiveTimeout(0.9, 50, 2000);
        foreach([50,60,80,120,150,200] as $v){ $a->observe($v); }
        $t1 = $a->timeout();
        foreach([500,600,700] as $v){ $a->observe($v); }
        $t2 = $a->timeout();
        assert($t2 >= $t1);
    }
}
