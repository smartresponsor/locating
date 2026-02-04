<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\TenantQuota;
final class TenantQuotaTest {
    public function testUpdate(): void {
        $q = new TenantQuota(0.3, 0.5, 1.5);
        $q->setBase('t','geocode', 100);
        $a1 = $q->update('t','geocode', 30, 0.02);
        $a2 = $q->update('t','geocode', 95, 0.15);
        assert($a1 >= 100 && $a2 <= 150);
    }
}
