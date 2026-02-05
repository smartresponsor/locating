<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\AbTestRouter;
final class AbTestRouterTest {
    public function testDecide(): void {
        $r = new AbTestRouter();
        $pick = $r->decide('t','geocode',['A'=>'p1','B'=>'p2'], 0.5);
        assert($pick==='p1' || $pick==='p2');
    }
}
