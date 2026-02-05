<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface ReverseAggregatorInterface
{
    public function reverse(float $lat, float $lon, ?string $region=null): array;
}