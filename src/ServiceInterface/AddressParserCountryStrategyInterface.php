<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 */

namespace App\ServiceInterface;

use App\EntityInterface\AddressInputInterface;
use App\EntityInterface\AddressDataInterface;

interface AddressParserCountryStrategyInterface
{
    public function supportCountryCode(?string $countryCode): bool;

    public function parse(AddressInputInterface $input): AddressDataInterface;
}

