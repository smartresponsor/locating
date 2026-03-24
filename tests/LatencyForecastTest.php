<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\LatencyForecast;
final class LatencyForecastTest {
    public function testForecast(): void {
        $f = new LatencyForecast(0.5);
        $f->observe('p','us', 200);
        $f->observe('p','us', 300);
        $x = $f->forecast('p','us');
        assert($x >= 200 && $x <= 300);
    }
}
