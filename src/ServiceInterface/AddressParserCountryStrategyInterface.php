<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 */

namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressInputInterface;
use Smartresponsor\EntityInterface\AddressDataInterface;

interface AddressParserCountryStrategyInterface
{
    public function supportCountryCode(?string $countryCode): bool;

    public function parse(AddressInputInterface $input): AddressDataInterface;
}

