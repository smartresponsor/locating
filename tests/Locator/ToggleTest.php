<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\Toggle;
final class ToggleTest {
    public function testPercentSplit(): void {
        $t = new Toggle();
        $t->set('canary-x', true, 50, '');
        $hit = 0;
        for ($i=0;$i<100;$i++){ if ($t->isEnabled('canary-x','', $i)) $hit++; }
        assert($hit > 30 && $hit < 70);
    }
}
