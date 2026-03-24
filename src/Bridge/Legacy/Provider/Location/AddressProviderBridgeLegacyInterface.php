<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

use Smartresponsor\Entity\Locator\GeoPoint;

interface AddressProviderBridgeLegacyInterface
{
    /** @param array<string,string> $componentMap */
    public function geocode(array $componentMap): ?GeoPoint;

    public function providerKey(): string;
}
