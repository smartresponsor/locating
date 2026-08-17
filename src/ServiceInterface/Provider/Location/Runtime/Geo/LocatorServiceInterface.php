<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Geo;

interface LocatorServiceInterface
{
    /** @return list<array<string, mixed>> */
    public function search(?float $lat, ?float $lon, int $radiusMeters, string $bbox): array;
}
