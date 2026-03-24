<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface AddressHintBiasInterface {
    /** Set weight 0..2 for region based on country code or hint tag. */
    public function set(string $tag, string $region, float $weight): void;
    /** Return multiplicative weight for region given hint set. */
    public function weight(array $hint, string $region): float;
}
