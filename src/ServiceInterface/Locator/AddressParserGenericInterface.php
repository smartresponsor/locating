<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AddressParserGenericInterface
{
    public function supportCountryCode(?string $countryCode): bool;
    public function parse(AddressInputInterface $input): AddressDataInterface;
}