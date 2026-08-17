<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\ServiceInterface\Address\Location;

interface AddressStandardizeServiceInterface
{
    /**
     * @param array<string, mixed> $input
     * @return array{line1:string,line2:string,city:string,region:string,postal:string,country:string,standardized:bool,verification:array{provider:string,status:string}}
     */
    public function standardize(array $input): array;
}
