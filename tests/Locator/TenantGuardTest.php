<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\TenantContext;
use Smartresponsor\Layer\Locator\TenantGuard;
final class TenantGuardTest {
    public function testAllow(): void {
        $ctx = new TenantContext('t1');
        $g = new TenantGuard();
        assert($g->allow($ctx, 't1') === true);
        assert($g->allow($ctx, 't2') === false);
    }
}
