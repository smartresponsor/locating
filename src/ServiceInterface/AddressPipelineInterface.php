<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressResult;

interface AddressPipelineInterface
{
    public function process(AddressInput $input): AddressResult;
}
