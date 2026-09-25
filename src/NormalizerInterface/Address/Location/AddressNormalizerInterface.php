<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\NormalizerInterface\Address\Location;

use App\Locating\ModelInterface\Location\AddressViewInterface;

interface AddressNormalizerInterface
{
    public function normalize(AddressViewInterface $address): AddressViewInterface;
}
