<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\CostRoutePlanner;
final class CostRoutePlannerTest {
    public function testOrder(): void {
        $p = new CostRoutePlanner();
        $rank = $p->order(['a','b'], ['a'=>1.0,'b'=>0.4], ['a'=>['latencyMs'=>500,'errorRate'=>0.05],'b'=>['latencyMs'=>250,'errorRate'=>0.02]]);
        assert($rank[0]==='b');
    }
}
