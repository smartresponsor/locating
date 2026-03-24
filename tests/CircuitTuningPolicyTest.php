<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\CircuitTuningPolicy;
final class CircuitTuningPolicyTest {
    public function testDecide(): void {
        $p = new CircuitTuningPolicy();
        $w1 = $p->window(0.01, 200);
        $t1 = $p->threshold(0.01, 200);
        $w2 = $p->window(0.5, 1200);
        $t2 = $p->threshold(0.5, 1200);
        assert($w2 > $w1 && $t2 < $t1);
    }
}
