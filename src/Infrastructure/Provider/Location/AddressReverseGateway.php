<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\Contract\Location\AddressReverseHttpBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\AddressReverseGatewayInterface;

final class AddressReverseGateway implements AddressReverseGatewayInterface
{
    public function __construct(private readonly AddressReverseHttpBackendInterface $backend)
    {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
    {
        return $this->backend->reverse($latitude, $longitude, $countryCode);
    }
}
