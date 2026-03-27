<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Address\Location;

use App\EntityInterface\Location\AddressViewInterface;

interface AddressNormalizerInterface
{
    public function normalize(AddressViewInterface $address): AddressViewInterface;
}
