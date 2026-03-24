<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface AddressParserGenericInterface
{
    public function supportCountryCode(?string $countryCode): bool;
    public function parse(AddressInputInterface $input): AddressDataInterface;
}