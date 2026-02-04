<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\RegionRouter;
use App\Layer\Locator\CircuitBreaker;
use App\Layer\Locator\Jitter;
final class RegionRouterTest {
    public function testSelect(): void {
        $rr = new RegionRouter();
        $pick = $rr->select(['us'=>0.9,'eu'=>0.7], ['us'=>1.0,'eu'=>1.2]);
        assert($pick === 'us');
    }
    public function testCircuit(): void {
        $cb = new CircuitBreaker(2);
        assert($cb->allow());
        $cb->onFailure(); $cb->onFailure();
        assert(!$cb->allow());
    }
    public function testJitter(): void {
        $j = new Jitter();
        $d = $j->backoffMs(2, 10, 200);
        assert($d >= 0 && $d <= 200);
    }
}
