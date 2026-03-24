<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\RateLimiterMemory;
final class RateLimiterMemoryTest {
    public function testAllow(): void {
        $r = new RateLimiterMemory();
        $ok1 = $r->allow('k', 2, 1000);
        $ok2 = $r->allow('k', 2, 1000);
        $ok3 = $r->allow('k', 2, 1000);
        assert($ok1===true && $ok2===true && $ok3===false);
    }
}
