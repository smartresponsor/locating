<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\AnomalyDetector;
final class AnomalyDetectorTest {
    public function testDetect(): void {
        $d = new AnomalyDetector();
        $ok = $d->detect([10,11,10,12,11,50], 2.0, 0.8);
        assert($ok===true);
    }
}
