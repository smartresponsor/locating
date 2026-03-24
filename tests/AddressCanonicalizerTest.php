<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\AddressCanonicalizer;
final class AddressCanonicalizerTest {
    public function testNormalize(): void {
        $c = new AddressCanonicalizer();
        $n = $c->normalize(['street'=>'  main  st ', 'city'=>'housTON', 'state'=>'tx', 'postal'=>' 77 001 ']);
        assert($n['city'] === 'Houston');
        assert($n['state'] === 'TX');
        assert($n['postal'] === '77001');
    }
}
