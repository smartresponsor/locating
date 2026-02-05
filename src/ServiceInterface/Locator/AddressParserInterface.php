<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\Entity\Locator\AddressData;

interface AddressParserInterface
{
    public function parse(AddressInput $input): AddressData;
}
