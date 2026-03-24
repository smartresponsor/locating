<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\RequestCoalescer;
final class RequestCoalescerTest {
    public function testCoalesce(): void {
        $c = new RequestCoalescer();
        $hit = 0;
        $r1 = $c->coalesce('k', function(){ return ['v'=>1]; });
        $r2 = $c->coalesce('k', function() use (&$hit){ $hit++; return ['v'=>2]; });
        assert(($r1['v']??0)===1 && ($r2['v']??0)===1 && $hit===0);
    }
}
