<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\TracePropagator;
final class TracePropagatorTest {
    public function testExtractInject(): void {
        $p = new TracePropagator();
        $ctx = $p->extract(['traceparent'=>'00-4bf92f3577b34da6-00f067aa0ba902b7-01']);
        $h = $p->inject([], $ctx);
        assert(isset($h['traceparent']));
    }
}
