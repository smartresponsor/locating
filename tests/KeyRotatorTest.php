<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\KeyRotator;
final class KeyRotatorTest {
    public function testActive(): void {
        $r = new KeyRotator();
        $now = 1_700_000_000;
        $r->register('prov','k1',$now-100,$now+10);
        $r->register('prov','k2',$now+10,$now+100);
        assert($r->active('prov', $now) === 'k1');
        assert($r->active('prov', $now+20) === 'k2');
    }
}
