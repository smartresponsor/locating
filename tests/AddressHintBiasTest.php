<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\AddressHintBias;
final class AddressHintBiasTest {
    public function testWeight(): void {
        $b = new AddressHintBias();
        $b->set('US','us', 1.5);
        $w = $b->weight(['US'], 'us');
        assert($w >= 1.4 && $w <= 1.6);
    }
}
