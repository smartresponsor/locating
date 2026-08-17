<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Geo;

interface CellIndexInterface
{
    /** Return cell id for lat/lon at level 0..15. */
    public function toCell(float $lat, float $lon, int $level): string;
    /** @return array{N:string,E:string,S:string,W:string} */
    public function neighbor(string $cellId): array;

    /**
     * @param array{0:float|int,1:float|int,2:float|int,3:float|int} $bbox
     * @return list<string>
     */
    public function cover(array $bbox, int $level): array;
}
