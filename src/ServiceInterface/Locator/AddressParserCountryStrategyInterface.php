<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\EntityInterface\Locator\AddressInputInterface;
use Smartresponsor\EntityInterface\Locator\AddressDataInterface;

interface AddressParserCountryStrategyInterface
{
    public function supportCountryCode(?string $countryCode): bool;

    public function parse(AddressInputInterface $input): AddressDataInterface;
}

