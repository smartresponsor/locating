<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\{FailoverPlanner,SlaPolicy};
final class FailoverPlannerTest {
    public function testPlan(): void {
        $p = new FailoverPlanner(new SlaPolicy());
        $rank = $p->plan('us',['p1','p2'], ['p1'=>['p95_ms'=>500,'error_rate'=>0.05],'p2'=>['p95_ms'=>200,'error_rate'=>0.01]]);
        assert($rank[0]==='p2');
    }
}
