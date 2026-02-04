<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\RegionTimeoutPolicy;
final class RegionTimeoutPolicyTest {
    public function testTimeout(): void {
        $p = new RegionTimeoutPolicy();
        $p->add('p','us','geocode', 800);
        $p->add('p','*','geocode', 900);
        assert($p->timeout('p','us','geocode', 1000)===800);
        assert($p->timeout('p','eu','geocode', 1000)===900);
        assert($p->timeout('x','eu','reverse', 1200)===1200);
    }
}
