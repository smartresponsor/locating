<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Address\Location;

use App\EntityInterface\Location\AddressReverseResultInterface;
use App\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\ServiceInterface\Provider\Location\AddressReverseProviderInterface;

final class AddressReverseCapability implements AddressReverseCapabilityInterface
{
    public function __construct(private readonly AddressReverseProviderInterface $provider)
    {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface
    {
        return $this->provider->reverse($latitude, $longitude, $countryCode);
    }
}
