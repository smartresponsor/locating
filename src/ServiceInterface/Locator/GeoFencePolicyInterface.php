<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface GeoFencePolicyInterface {
    /** Register rectangular fence tagged by region. */
    public function add(string $name, string $region, float $minLat, float $minLon, float $maxLat, float $maxLon): void;
    /** Return assigned region or default if none matched. */
    public function decide(float $lat, float $lon, string $defaultRegion='us'): string;
    /** Return true if point is inside any fence tagged as allowed for region. */
    public function allow(float $lat, float $lon, string $region): bool;
}
