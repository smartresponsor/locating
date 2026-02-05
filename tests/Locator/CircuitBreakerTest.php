<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\CircuitBreaker;
final class CircuitBreakerTest {
    public function testTripAndHalf(): void {
        $c = new CircuitBreaker(2, 0, 1);
        assert($c->allow('k') === true);
        $c->onResult('k', false); $c->onResult('k', false);
        assert($c->allow('k') === false); // open
        // half-open allowed (halfAfterS=0)
        assert($c->allow('k') === true);
        $c->onResult('k', true);
        assert($c->allow('k') === true);
    }
}
