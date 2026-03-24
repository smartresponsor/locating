<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\{ProviderOrder, ScoreEnsemble, HealthEwma, BanditPolicy};
final class ProviderOrderTest {
    public function testRank(): void {
        $o = new ProviderOrder(new ScoreEnsemble(), new HealthEwma());
        $b = new BanditPolicy(0.0);
        $b->update('p2', 1.0);
        $rank = $o->rank([
            'p1'=>['latency_ms'=>200,'error_rate'=>0.02,'unit_cost'=>1.0],
            'p2'=>['latency_ms'=>150,'error_rate'=>0.01,'unit_cost'=>1.5]
        ], $b);
        assert($rank[0] === 'p2');
    }
}
