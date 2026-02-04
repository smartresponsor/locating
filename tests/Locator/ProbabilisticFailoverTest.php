<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\ProbabilisticFailover;
final class ProbabilisticFailoverTest {
    public function testShould(): void {
        $f = new ProbabilisticFailover(0.0);
        $hit = 0;
        for($i=0;$i<200;$i++){ if ($f->should(0.1, 0.5)) { $hit++; } }
        assert($hit > 0);
    }
}
