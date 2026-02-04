<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\ResultCache;
final class ResultCacheTest {
    public function testCache(): void {
        $c = new ResultCache();
        $k = $c->key(['q'=>'Main St, Houston']);
        assert($c->get($k) === null);
        $c->set($k, ['lat'=>29.76,'lon'=>-95.37], 60);
        $v = $c->get($k);
        assert(isset($v['_ttl']) && $v['lat']===29.76);
    }
}
