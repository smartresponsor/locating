<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\BanditPolicy;
final class BanditPolicyTest {
    public function testUpdateAndSelect(): void {
        $b = new BanditPolicy(0.0);
        $b->update('p1', 0.3);
        $b->update('p2', 0.7);
        $sel = $b->select(['p1'=>1,'p2'=>1]);
        assert($sel === 'p2');
    }
}
