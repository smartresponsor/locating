<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\FactoryInterface\Http\Location\LocationViewFactoryInterface;
use App\Locating\ModelInterface\Location\AddressReverseViewInterface;
use App\Locating\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\Locating\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;

final class LocationAddressReverseService implements LocationAddressReverseServiceInterface
{
    public function __construct(
        private readonly AddressReverseCapabilityInterface $inner,
        private readonly LocationViewFactoryInterface $viewFactory,
    ) {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseViewInterface
    {
        return $this->viewFactory->createReverseView(
            $this->inner->reverse($latitude, $longitude, $countryCode),
        );
    }
}
