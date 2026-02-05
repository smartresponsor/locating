<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\RetryPolicy;
final class RetryPolicyTest {
    public function testShouldRetry(): void {
        $p = new RetryPolicy();
        assert($p->shouldRetry(0, 3, 500) === true);
        assert($p->shouldRetry(3, 3, 500) === false);
        assert($p->shouldRetry(1, 3, 404) === false);
    }
    public function testDelayRange(): void {
        $p = new RetryPolicy();
        $d = $p->delayMs(2, 10, 200);
        assert($d >= 0 && $d <= 200);
    }
}
