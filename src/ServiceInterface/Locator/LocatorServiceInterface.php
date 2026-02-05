<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\ServiceInterface\Locator\Locator;

interface LocatorServiceInterface
{
    public function search(?float $lat, ?float $lon, int $radiusMeters, string $bbox): array;
}
