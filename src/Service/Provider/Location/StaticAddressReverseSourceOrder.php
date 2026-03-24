<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\ServiceInterface\Provider\Location\AddressReverseSourceInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceOrderInterface;

final class StaticAddressReverseSourceOrder implements AddressReverseSourceOrderInterface
{
    public function order(iterable $sources, float $latitude, float $longitude, ?string $countryCode = null): array
    {
        $ordered = [];
        foreach ($sources as $source) {
            if ($source instanceof AddressReverseSourceInterface) {
                $ordered[] = $source;
            }
        }

        return $ordered;
    }
}
