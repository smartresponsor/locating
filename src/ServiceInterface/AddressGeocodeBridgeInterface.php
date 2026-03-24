<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

use App\Entity\AddressData;
use App\Entity\AddressResult;

interface AddressGeocodeBridgeInterface
{
    public function enrich(AddressData $address, AddressResult $result): AddressResult;
}
