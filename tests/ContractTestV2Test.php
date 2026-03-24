<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\ContractTestV2;
final class ContractTestV2Test {
    public function testValidate(): void {
        $t = new ContractTestV2();
        $ok = $t->validate('any','geocode','v2', ['lat'=>1,'lon'=>2,'providerId'=>'mock','normalized'=>['text'=>'x']]);
        $bad = $t->validate('any','geocode','v2', ['lat'=>1,'providerId'=>'mock']);
        assert($ok===[] && in_array('missing:lon', $bad, true));
    }
}
