<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Address\Location;

use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;

interface AddressValidatorInterface
{
    public function validate(AddressViewInterface $address): AddressPipelineResultInterface;
}
