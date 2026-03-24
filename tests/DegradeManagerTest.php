<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\DegradeManager;
final class DegradeManagerTest {
    public function testDecide(): void {
        $d = new DegradeManager();
        assert($d->decide('geocode', 0.9, 0.01, true)==='none');
        assert($d->decide('geocode', 0.5, 0.2, true)==='cache-stale');
        assert($d->decide('reverse', 0.4, 0.05, false)==='reduced-precision');
    }
}
