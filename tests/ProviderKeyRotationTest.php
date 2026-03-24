<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\ProviderKeyRotation;
final class ProviderKeyRotationTest {
    public function testRotate(): void {
        $r = new ProviderKeyRotation();
        $v1 = $r->active('p','t','us');
        $v2 = $r->rotate('p','t','us');
        assert($v2 !== $v1);
        assert($r->active('p','t','us') === $v2);
    }
}
