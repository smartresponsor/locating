<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface ReverseAggregatorInterface
{
    public function reverse(float $lat, float $lon, ?string $region=null): array;
}