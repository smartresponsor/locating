<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\AddressReverseGatewayInterface;
use App\InfrastructureInterface\Provider\Location\AddressReverseHttpBackendInterface;

final class LegacyAddressReverseGateway implements AddressReverseGatewayInterface
{
    public function __construct(private readonly AddressReverseHttpBackendInterface $backend)
    {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
    {
        return $this->backend->reverse($latitude, $longitude, $countryCode);
    }
}
