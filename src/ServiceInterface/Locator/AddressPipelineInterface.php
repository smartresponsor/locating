<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

use App\Entity\Locator\AddressInput;
use App\Entity\Locator\AddressResult;

interface AddressPipelineInterface
{
    public function process(AddressInput $input): AddressResult;
}
