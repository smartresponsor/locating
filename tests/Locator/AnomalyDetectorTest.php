<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\AnomalyDetector;
final class AnomalyDetectorTest {
    public function testDetect(): void {
        $d = new AnomalyDetector();
        $ok = $d->detect([10,11,10,12,11,50], 2.0, 0.8);
        assert($ok===true);
    }
}
