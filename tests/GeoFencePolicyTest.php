<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\GeoFencePolicy;
final class GeoFencePolicyTest {
    public function testDecideAllow(): void {
        $p = new GeoFencePolicy();
        $p->add('houston','us', 29.5, -95.8, 30.2, -95.0);
        assert($p->decide(29.76, -95.37, 'eu') === 'us');
        assert($p->allow(29.76, -95.37, 'us') === true);
        assert($p->allow(51.5, -0.1, 'us') === false);
    }
}
