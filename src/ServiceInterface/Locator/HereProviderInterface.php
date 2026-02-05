<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface HereProviderInterface
{
    public function name(): string;
    public function geocode(string $q, string $country): array;
    public function reverse(float $lat, float $lon): array;
    public function autocomplete(string $q, string $country, string $bbox): array;
}