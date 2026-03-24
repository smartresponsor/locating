<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

interface LocationLocatorServiceInterface
{
    public function search(?float $lat, ?float $lon, int $radiusMeters, string $bbox): array;
}
