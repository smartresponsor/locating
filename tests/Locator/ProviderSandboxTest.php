<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\ProviderSandbox;
use App\Layer\Locator\FakeProvider;
final class ProviderSandboxTest {
    public function testRoute(): void {
        $s = new ProviderSandbox();
        $s->register('prov-a', new FakeProvider('A', 29.76, -95.37));
        $r = $s->route('prov-a', ['q'=>'Main St']);
        assert($r['status']==='ok' && $r['_provider']==='prov-a');
    }
}
