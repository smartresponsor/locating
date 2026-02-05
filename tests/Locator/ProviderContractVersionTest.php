<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\ProviderContractVersion;
final class ProviderContractVersionTest {
    public function testSelectSupport(): void {
        $v = new ProviderContractVersion();
        $v->register('mock','geocode','v1');
        $v->register('mock','geocode','v2');
        assert($v->select('mock','geocode', null)==='v2');
        assert($v->support('mock','geocode','v1')===true);
    }
}
