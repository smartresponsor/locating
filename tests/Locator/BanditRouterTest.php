<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\BanditRouter;
final class BanditRouterTest {
    public function testExploreExploit(): void {
        $b = new BanditRouter();
        $prov = ['a','b'];
        // simulate 'b' being better
        for($i=0;$i<50;$i++){
            $pick = $b->select('us','geocode',$prov);
            $reward = $pick==='b' ? 1.0 : 0.5;
            $b->update('us','geocode',$pick,$reward);
        }
        $pick = $b->select('us','geocode',$prov);
        assert($pick==='b');
    }
}
