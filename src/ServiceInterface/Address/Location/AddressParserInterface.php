<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Address\Location;

use App\Locating\ModelInterface\Location\AddressInputInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;

interface AddressParserInterface
{
    public function parse(AddressInputInterface $input): AddressViewInterface;
}
