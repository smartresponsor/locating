<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\CostAwarePolicy;
final class CostAwarePolicyTest {
    public function testScore(): void {
        $p = new CostAwarePolicy();
        $s1 = $p->score(0.9, 0.5);
        $s2 = $p->score(0.5, 2.0);
        assert($s1 > $s2);
    }
}
