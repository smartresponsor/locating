<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ProviderCostCatalogInterface {
    /** Return unit cost for op type in region (fallback if not set). */
    public function cost(string $providerId, string $region, string $op): float;
    /** Set or override cost. */
    public function set(string $providerId, string $region, string $op, float $unit): void;
}
