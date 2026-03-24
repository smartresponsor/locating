<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\Entity\AddressData;
use Smartresponsor\Entity\AddressResult;

interface AddressGeocodeBridgeInterface
{
    public function enrich(AddressData $address, AddressResult $result): AddressResult;
}
