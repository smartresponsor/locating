<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\CanaryGuard;
final class CanaryGuardTest {
    public function testDecide(): void {
        $g = new CanaryGuard(0.02, 800, 1.2);
        assert($g->decide(0.03, 700, 1.0)==='pause');
        assert($g->decide(0.03, 900, 1.3)==='rollback');
        assert($g->decide(0.005, 600, 0.9)==='continue');
    }
}
