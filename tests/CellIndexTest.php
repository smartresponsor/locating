<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\CellIndex;
final class CellIndexTest {
    public function testCellAndCover(): void {
        $c = new CellIndex();
        $id = $c->toCell(37.4219,-122.0840, 8);
        $nb = $c->neighbor($id);
        $cov = $c->cover([37.4,-122.2,37.5,-122.0], 8);
        assert(isset($nb['N']) && count($cov) >= 1);
    }
}
