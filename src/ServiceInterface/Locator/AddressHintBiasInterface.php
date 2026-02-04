<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface AddressHintBiasInterface {
    /** Set weight 0..2 for region based on country code or hint tag. */
    public function set(string $tag, string $region, float $weight): void;
    /** Return multiplicative weight for region given hint set. */
    public function weight(array $hint, string $region): float;
}
