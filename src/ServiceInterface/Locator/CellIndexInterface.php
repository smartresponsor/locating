<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface CellIndexInterface {
    /** Return cell id for lat/lon at level 0..15. */
    public function toCell(float $lat, float $lon, int $level): string;
    /** Return neighbor cell ids (N,E,S,W). */
    public function neighbor(string $cellId): array;
    /** Return list of cells covering bbox [lat1,lon1,lat2,lon2]. */
    public function cover(array $bbox, int $level): array;
}
