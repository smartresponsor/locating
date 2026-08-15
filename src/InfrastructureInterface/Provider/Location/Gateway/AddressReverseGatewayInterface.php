<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Gateway;

interface AddressReverseGatewayInterface
{
    /**
     * @return array<string, mixed>
     */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array;
}
