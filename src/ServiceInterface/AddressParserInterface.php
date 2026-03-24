<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressData;

interface AddressParserInterface
{
    public function parse(AddressInput $input): AddressData;
}
