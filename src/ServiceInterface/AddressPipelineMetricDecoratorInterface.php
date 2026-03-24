<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface AddressPipelineMetricDecoratorInterface
{
    public function process(AddressInput $input): AddressResult;
}