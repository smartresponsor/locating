<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\InfrastructureInterface;

/**
 * Low-level HTTP client for reverse geocoding.
 * Implementations are responsible for calling external APIs and returning
 * a provider-specific payload that AddressReverse service can interpret.
 */
interface ReverseHttpClientInterface
{
    /**
     * Perform reverse geocoding for the given coordinate.
     *
     * @return array<mixed> Raw provider payload.
     */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array;
}
