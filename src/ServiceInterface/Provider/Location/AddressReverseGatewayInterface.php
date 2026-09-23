<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

interface AddressReverseGatewayInterface
{
    /**
     * @return array<string, mixed>
     */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array;
}
