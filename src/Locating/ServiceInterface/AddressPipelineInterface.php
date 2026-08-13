<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface;

use App\Locating\Model\AddressInput;
use App\Locating\Model\AddressPipelineResult;

interface AddressPipelineInterface
{
    public function process(AddressInput $input): AddressPipelineResult;
}
