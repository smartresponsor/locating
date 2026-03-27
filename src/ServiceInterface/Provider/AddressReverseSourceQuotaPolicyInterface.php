<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

interface AddressReverseSourceQuotaPolicyInterface
{
    public function allows(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): bool;
}
