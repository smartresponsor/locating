<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface MapboxProviderInterface
{
    public function name(): string;
    public function geocode(string $q, string $country): array;
    public function reverse(float $lat, float $lon): array;
    public function autocomplete(string $q, string $country, string $bbox): array;
}