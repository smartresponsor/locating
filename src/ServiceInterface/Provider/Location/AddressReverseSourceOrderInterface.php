<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

interface AddressReverseSourceOrderInterface
{
    /**
     * @param iterable<AddressReverseSourceInterface> $sources
     *
     * @return list<AddressReverseSourceInterface>
     */
    public function order(iterable $sources, float $latitude, float $longitude, ?string $countryCode = null): array;
}
