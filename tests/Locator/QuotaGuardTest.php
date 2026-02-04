<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\QuotaGuard;
final class QuotaGuardTest {
    public function testCharge(): void {
        $q = new QuotaGuard();
        $q->setLimit('t1', 2, 1.0);
        assert($q->charge('t1', 0.4) === true);
        assert($q->charge('t1', 0.5) === true);
        assert($q->charge('t1', 0.2) === false); // cost cap
    }
}
