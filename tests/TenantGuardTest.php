<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\TenantContext;
use Smartresponsor\Layer\TenantGuard;
final class TenantGuardTest {
    public function testAllow(): void {
        $ctx = new TenantContext('t1');
        $g = new TenantGuard();
        assert($g->allow($ctx, 't1') === true);
        assert($g->allow($ctx, 't2') === false);
    }
}
