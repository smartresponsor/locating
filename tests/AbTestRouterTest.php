<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\AbTestRouter;
final class AbTestRouterTest {
    public function testDecide(): void {
        $r = new AbTestRouter();
        $pick = $r->decide('t','geocode',['A'=>'p1','B'=>'p2'], 0.5);
        assert($pick==='p1' || $pick==='p2');
    }
}
