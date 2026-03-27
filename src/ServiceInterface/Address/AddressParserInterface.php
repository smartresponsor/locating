<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Address\Location;

use App\EntityInterface\Location\AddressInputInterface;
use App\EntityInterface\Location\AddressViewInterface;

interface AddressParserInterface
{
    public function parse(AddressInputInterface $input): AddressViewInterface;
}
