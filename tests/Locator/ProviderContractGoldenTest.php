<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\ProviderContractGolden;
final class ProviderContractGoldenTest {
    public function testGolden(): void {
        $g = new ProviderContractGolden(sys_get_temp_dir().'/golden');
        $req = ['text'=>'1600 Amphitheatre Parkway'];
        $res1 = ['lat'=>37.422,'lon'=>-122.084,'providerId'=>'mock'];
        $res2 = ['lat'=>37.422,'lon'=>-122.084,'providerId'=>'mock'];
        $g->record('mock','geocode',$req,$res1);
        assert($g->verify('mock','geocode',$req,$res2,false)===true);
    }
}
