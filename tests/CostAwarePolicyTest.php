<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\CostAwarePolicy;
final class CostAwarePolicyTest {
    public function testScore(): void {
        $p = new CostAwarePolicy();
        $s1 = $p->score(0.9, 0.5);
        $s2 = $p->score(0.5, 2.0);
        assert($s1 > $s2);
    }
}
