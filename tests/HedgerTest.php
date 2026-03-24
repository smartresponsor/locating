<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\Hedger;
final class HedgerTest {
    public function testOffsets(): void {
        $h = new Hedger();
        $arr = $h->offsets(50, 800);
        assert($arr[0]===0 && $arr[count($arr)-1] <= 800);
        for($i=1;$i<count($arr);$i++){ assert($arr[$i] > $arr[$i-1]); }
    }
}
