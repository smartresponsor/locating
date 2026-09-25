<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\PolicyInterface\Provider\Location;

interface AddressReverseSourceCostPolicyInterface
{
    public function penalty(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): float;
}
