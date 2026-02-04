<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\{BudgetGuard, CostCapPolicy};
final class BudgetGuardTest {
    public function testBudget(): void {
        $g = new BudgetGuard(['t'=>['geocode'=>['cap'=>10.0,'used'=>0.0]]]);
        $cap = new CostCapPolicy();
        assert($cap->allow(1.5, 2.0)===true);
        assert($g->allow('t','geocode', 3.0)===true);
        assert($g->allow('t','geocode', 8.0)===false);
        assert($g->remaining('t','geocode')===7.0);
    }
}
