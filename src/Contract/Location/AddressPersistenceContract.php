<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

use App\Locating\Model\Location\AddressData;

interface AddressPersistenceContract
{
    public function persist(AddressData $address): void;
}
