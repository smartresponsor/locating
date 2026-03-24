<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

use App\Entity\AddressInput;
use App\Entity\AddressResult;

interface AddressPipelineInterface
{
    public function process(AddressInput $input): AddressResult;
}
