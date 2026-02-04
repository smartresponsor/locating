<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\{ResultCache, CacheInvalidator, CacheTtlPolicy};
final class CacheInvalidationTest {
    public function testKeyAndPrefix(): void {
        $c = new ResultCache();
        $inv = new CacheInvalidator($c);
        $c->put('addr:one', ['v'=>1], 100); $inv->index('addr:one');
        $c->put('addr:two', ['v'=>2], 100); $inv->index('addr:two');
        $n = $inv->prefix('addr:');
        assert($n >= 2);
        assert($c->get('addr:one') === null && $c->get('addr:two') === null);
    }
}
