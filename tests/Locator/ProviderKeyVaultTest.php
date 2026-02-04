<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\ProviderKeyVault;
final class ProviderKeyVaultTest {
    public function testRotate(): void {
        $v = new ProviderKeyVault();
        $now = time();
        $v->add('p','k1','s', 10, $now-10, $now+1000);
        $v->add('p','k2','s', 20, $now-10, $now+1000);
        assert($v->current('p')==='k2');
        assert($v->rotate('p')!==null);
    }
}
