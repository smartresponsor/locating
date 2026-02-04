<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace SmartResponsor\ServiceInterface\Locator\Address;

interface AddressParseServiceInterface
{
    public function parse(string $address, string $locale): array;
}
