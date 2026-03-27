<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Entity\Location;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\Entity\Locator\GeoPoint;

interface AddressResultLegacyInterface
{
    public function status(): AddressStatus;

    public function addressData(): ?AddressData;

    /**
     * @return AddressValidationIssueLegacyInterface[]
     */
    public function issues(): array;

    public function geoPoint(): ?GeoPoint;

    public function providerKey(): ?string;

    public function toArray(): array;
}
