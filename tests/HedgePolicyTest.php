<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\HedgePolicy;
final class HedgePolicyTest {
    public function testDelay(): void {
        $h = new HedgePolicy(0.8, 10, 2000);
        $d = $h->delayMs([50,60,70,80,90,100,200,400,800]);
        assert($d >= 10 && $d <= 2000);
    }
}
