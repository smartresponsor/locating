<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\CacheProbe;
final class CacheProbeTest {
    public function testCheckDiff(): void {
        $p = new CacheProbe();
        $c = ['a'=>1,'b'=>2];
        $ok = $p->check('k', $c, fn($k)=>hash('sha256', json_encode($c)));
        $diff = $p->diff(['a'=>1], ['a'=>1,'x'=>2]);
        assert($ok===true && in_array('extra:x',$diff,true));
    }
}
