<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\TimeoutCalibrator;
final class TimeoutCalibratorTest {
    public function testCalibrate(): void {
        $c = new TimeoutCalibrator(200, 3000);
        assert($c->calibrate(800, 1200, 0.01) >= 900);
        assert($c->calibrate(800, 500, 0.0) <= 800);
    }
}
