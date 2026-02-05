<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\Entity\Locator\AddressData;

interface AddressNormalizerInterface
{
    public function normalize(AddressData $address): AddressData;
}
