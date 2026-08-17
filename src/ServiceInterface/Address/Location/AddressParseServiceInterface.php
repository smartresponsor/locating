<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\ServiceInterface\Address\Location;

interface AddressParseServiceInterface
{
    /** @return array<string,mixed> */
    public function parse(string $address, string $locale): array;
}
