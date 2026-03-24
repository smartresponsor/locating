<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\TenantSeparator;
final class TenantSeparatorTest {
    public function testSchemaAndKey(): void {
        $t = new TenantSeparator();
        $s = $t->schema('ACME-1');
        assert(str_starts_with($s, 't_'));
        $k = $t->storageKey('ACME-1', 'rate:geocode');
        assert(str_contains($k, ':') && str_starts_with($k, $s.':'));
    }
}
