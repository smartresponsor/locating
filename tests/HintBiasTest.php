<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\HintBias;
final class HintBiasTest {
    public function testRegionAndLocale(): void {
        $b = new HintBias();
        assert($b->region(['country'=>'DE']) === 'eu');
        assert($b->region(['country'=>'US']) === 'us');
        assert($b->locale(['country'=>'US']) === 'en');
    }
}
