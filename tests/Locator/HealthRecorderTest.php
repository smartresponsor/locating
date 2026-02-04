<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\HealthRecorder;
final class HealthRecorderTest {
    public function testSnapshot(): void {
        $r = new HealthRecorder();
        $r->ok('p:us', 120); $r->fail('p:us', 500);
        $s = $r->snapshot('p:us');
        assert($s['ok']===1 && $s['fail']===1 && $s['avg_ms']>0 && $s['error_rate']>0);
    }
}
