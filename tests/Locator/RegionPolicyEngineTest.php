<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\RegionPolicyEngine;
final class RegionPolicyEngineTest {
    public function testDecide(): void {
        $e = new RegionPolicyEngine();
        $e->set(['when'=>['country'=>'US'], 'region'=>'us']);
        $e->set(['when'=>['country'=>'EU'], 'region'=>'eu']);
        $r = $e->decide(['country'=>'US','lat'=>37.4,'lon'=>-122.0]);
        assert($r==='us');
    }
}
