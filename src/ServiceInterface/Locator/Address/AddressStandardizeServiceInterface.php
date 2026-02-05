<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\ServiceInterface\Locator\Address;

interface AddressStandardizeServiceInterface
{
    public function standardize(array $input): array;
}
