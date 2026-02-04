<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\FailoverMatrix;
final class FailoverMatrixTest {
    public function testCandidate(): void {
        $m = new FailoverMatrix();
        $m->set('us','p1',['p2','p3','p2']);
        $c = $m->candidate('us','p1');
        assert($c[0]==='p1' && in_array('p2',$c,true) && in_array('p3',$c,true));
    }
}
