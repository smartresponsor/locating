<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\TenantResourceQuota;
final class TenantResourceQuotaTest {
    public function testConsume(): void {
        $q = new TenantResourceQuota();
        $q->setCap('t','geocode','provider:p1', 2);
        assert($q->consume('t','geocode','provider:p1',1)===true);
        assert($q->consume('t','geocode','provider:p1',1)===true);
        assert($q->consume('t','geocode','provider:p1',1)===false);
    }
}
