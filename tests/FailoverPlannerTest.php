<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\{FailoverPlanner,SlaPolicy};
final class FailoverPlannerTest {
    public function testPlan(): void {
        $p = new FailoverPlanner(new SlaPolicy());
        $rank = $p->plan('us',['p1','p2'], ['p1'=>['p95_ms'=>500,'error_rate'=>0.05],'p2'=>['p95_ms'=>200,'error_rate'=>0.01]]);
        assert($rank[0]==='p2');
    }
}
