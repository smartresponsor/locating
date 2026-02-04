<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

use App\EntityInterface\Locator\AddressInputInterface;
use App\EntityInterface\Locator\AddressDataInterface;

interface AddressParserCountryStrategyInterface
{
    public function supportCountryCode(?string $countryCode): bool;

    public function parse(AddressInputInterface $input): AddressDataInterface;
}

