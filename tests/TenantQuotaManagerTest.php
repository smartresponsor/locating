<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\TenantQuotaManager;
final class TenantQuotaManagerTest {
    public function testAllowAndRemaining(): void {
        $q = new TenantQuotaManager(['t'=>['geocode'=>['limit'=>2,'used'=>0]]]);
        assert($q->allow('t','geocode',1)===true);
        assert($q->allow('t','geocode',1)===true);
        assert($q->allow('t','geocode',1)===false);
        assert($q->remaining('t','geocode')===0);
    }
}
