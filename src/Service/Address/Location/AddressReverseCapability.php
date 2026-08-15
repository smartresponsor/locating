<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseProviderInterface;

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
