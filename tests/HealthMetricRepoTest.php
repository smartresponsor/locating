<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\HealthMetric;
final class HealthMetricRepoTest {
    public function testSerialize(): void {
        $m = new HealthMetric();
        $row = $m->serialize('prov','us',0.87,1700000000);
        assert($row['provider']==='prov' && $row['region']==='us' && $row['health']>0);
    }
}
