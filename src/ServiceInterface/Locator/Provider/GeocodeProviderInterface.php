<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\ServiceInterface\Locator\Provider;

interface GeocodeProviderInterface
{
    public function geocode(string $q, string $country): array;
    public function reverse(float $lat, float $lon): array;
    public function autocomplete(string $q, string $country, string $bbox): array;
    public function name(): string;
}
