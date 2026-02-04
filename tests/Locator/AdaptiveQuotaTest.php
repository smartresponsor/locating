<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\AdaptiveQuota;
final class AdaptiveQuotaTest {
    public function testTightenAndLoosen(): void {
        $q = new AdaptiveQuota();
        $tight = $q->compute('t', 900, 90.0, 1000, 100.0, 0.10);
        assert($tight['req_limit'] < 1000 && $tight['cost_limit'] < 100.0);
        $loose = $q->compute('t', 100, 10.0, 1000, 100.0, 0.005);
        assert($loose['req_limit'] >= 1000 && $loose['cost_limit'] >= 100.0);
    }
}
