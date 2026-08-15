<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;

interface AddressReverseResultNormalizerInterface
{
    public function normalize(AddressReverseResultInterface $result, float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface;
}
