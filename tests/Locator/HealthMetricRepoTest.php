<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\HealthMetric;
final class HealthMetricRepoTest {
    public function testSerialize(): void {
        $m = new HealthMetric();
        $row = $m->serialize('prov','us',0.87,1700000000);
        assert($row['provider']==='prov' && $row['region']==='us' && $row['health']>0);
    }
}
