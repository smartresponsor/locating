<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\AdaptiveTimeoutCalibrator;
final class AdaptiveTimeoutCalibratorTest {
    public function testCalibrate(): void {
        $c = new AdaptiveTimeoutCalibrator(200, 1500);
        $x = $c->calibrate(600, 0.05, 400);
        assert($x >= 400 && $x <= 1500);
        $y = $c->calibrate(1200, 0.3, $x);
        assert($y > $x);
    }
}
