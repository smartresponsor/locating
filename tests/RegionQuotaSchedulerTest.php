<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\RegionQuotaScheduler;
final class RegionQuotaSchedulerTest {
    public function testAllocatePick(): void {
        $s = new RegionQuotaScheduler();
        $alloc = $s->allocate('t','geocode', 10, ['us'=>1,'eu'=>1]);
        assert($alloc['us']+$alloc['eu']===10);
        $pick = $s->pick(['us'=>6,'eu'=>4]);
        assert($pick==='us');
    }
}
