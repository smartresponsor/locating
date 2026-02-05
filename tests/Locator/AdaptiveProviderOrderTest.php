<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\AdaptiveProviderOrder;
final class AdaptiveProviderOrderTest {
    public function testRank(): void {
        $r = new AdaptiveProviderOrder();
        $order = $r->rank(['a'=>['p95Ms'=>500,'errorRate'=>0.05,'costAvg'=>1.0],'b'=>['p95Ms'=>250,'errorRate'=>0.02,'costAvg'=>0.5]]);
        assert($order[0]==='b');
    }
}
