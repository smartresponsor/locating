<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\HealthEwma;
use App\Layer\RouteDecision;
final class HealthEwmaTest {
    public function testHealthAndChoose(): void {
        $h = new HealthEwma(0.5);
        $a = $h->health(80, 0.02);
        $b = $h->health(300, 0.10);
        assert($a > $b);
        $d = new RouteDecision();
        $pick = $d->choose(['A'=>$a, 'B'=>$b]);
        assert($pick === 'A');
    }
}
