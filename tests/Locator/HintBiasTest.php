<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\HintBias;
final class HintBiasTest {
    public function testRegionAndLocale(): void {
        $b = new HintBias();
        assert($b->region(['country'=>'DE']) === 'eu');
        assert($b->region(['country'=>'US']) === 'us');
        assert($b->locale(['country'=>'US']) === 'en');
    }
}
