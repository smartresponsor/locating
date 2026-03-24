<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\RegionPolicyEngine;
final class RegionPolicyEngineTest {
    public function testDecide(): void {
        $e = new RegionPolicyEngine();
        $e->set(['when'=>['country'=>'US'], 'region'=>'us']);
        $e->set(['when'=>['country'=>'EU'], 'region'=>'eu']);
        $r = $e->decide(['country'=>'US','lat'=>37.4,'lon'=>-122.0]);
        assert($r==='us');
    }
}
