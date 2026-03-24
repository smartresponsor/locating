<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\ProviderStatAggregator;
final class ProviderStatAggregatorTest {
    public function testSnapshot(): void {
        $a = new ProviderStatAggregator(0.5);
        $a->observe('p','us', 200, true, 0.5);
        $a->observe('p','us', 400, false, 0.6);
        $snap = $a->snapshot();
        assert(count($snap)===1 && $snap[0]['count']===2);
    }
}
