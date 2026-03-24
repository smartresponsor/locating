<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\{AdaptiveOrdering, HealthEwma, CostAwarePolicy, SlaPolicy, AddressHintBias};
final class AdaptiveOrderingTest {
    public function testOrder(): void {
        $ord = new AdaptiveOrdering(new HealthEwma(), new CostAwarePolicy(), new SlaPolicy(), (function(){ $b=new AddressHintBias(); $b->set('US','us',1.2); return $b;})());
        $rank = $ord->order('us',['p1','p2'], ['p1'=>['latency_ms'=>500,'error_rate'=>0.05], 'p2'=>['latency_ms'=>200,'error_rate'=>0.01]], ['p1'=>1.0,'p2'=>1.2], ['US']);
        assert($rank[0]==='p2');
    }
}
