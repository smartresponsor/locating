<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\SlaPolicy;
final class SlaPolicyTest {
    public function testWeight(): void {
        $p = new SlaPolicy();
        $w1 = $p->weight(300, 280, 0.02, 0.01);
        $w2 = $p->weight(300, 600, 0.02, 0.05);
        assert($w1 > $w2);
    }
}
