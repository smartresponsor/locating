<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

use App\Entity\Locator\AddressData;

interface AddressNormalizerInterface
{
    public function normalize(AddressData $address): AddressData;
}
