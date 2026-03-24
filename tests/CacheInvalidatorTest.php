<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\CacheInvalidator;
final class CacheInvalidatorTest {
    public function testTtl(): void {
        $i = new CacheInvalidator();
        $created = 1000; $ttl = 10;
        assert($i->shouldInvalidate($created, $ttl, 1005) === false);
        assert($i->shouldInvalidate($created, $ttl, 1011) === true);
    }
    public function testPrefix(): void {
        $i = new CacheInvalidator();
        assert($i->match('locator:us:abc','locator:us:') === true);
        assert($i->match('locator:eu:abc','locator:us:') === false);
    }
}
