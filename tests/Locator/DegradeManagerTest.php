<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\DegradeManager;
final class DegradeManagerTest {
    public function testDecide(): void {
        $d = new DegradeManager();
        assert($d->decide('geocode', 0.9, 0.01, true)==='none');
        assert($d->decide('geocode', 0.5, 0.2, true)==='cache-stale');
        assert($d->decide('reverse', 0.4, 0.05, false)==='reduced-precision');
    }
}
