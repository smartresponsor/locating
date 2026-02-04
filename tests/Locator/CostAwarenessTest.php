<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\CostAwareness;
final class CostAwarenessTest {
    public function testBudgetEffect(): void {
        $c = new CostAwareness();
        $a = $c->score(120, 0.8, 0.02, 0.0);
        $b = $c->score(120, 0.8, 0.02, 0.5);
        assert($b > $a);
    }
}
