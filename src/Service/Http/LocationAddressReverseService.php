<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Http\Location;

use App\EntityInterface\Location\AddressReverseViewInterface;
use App\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;
use App\ServiceInterface\Http\Location\LocationViewFactoryInterface;

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
