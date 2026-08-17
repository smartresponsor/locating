<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\Infrastructure\Location\Config\Env;
use App\Locating\ServiceInterface\Address\Location\AddressStandardizeServiceInterface;

class AddressStandardizeService implements AddressStandardizeServiceInterface
{
    public function __construct(Env $env)
    {
        unset($env);
    }

    /**
     * @param array<string, mixed> $input
     * @return array{line1:string,line2:string,city:string,region:string,postal:string,country:string,standardized:bool,verification:array{provider:string,status:string}}
     */
    public function standardize(array $input): array
    {
        $string = static fn (mixed $value): string => is_string($value) ? $value : '';
        $std = [
            'line1' => $string($input['line1'] ?? null),
            'line2' => $string($input['line2'] ?? null),
            'city' => $string($input['city'] ?? null),
            'region' => $string($input['region'] ?? null),
            'postal' => strtoupper($string($input['postal'] ?? null)),
            'country' => strtoupper($string($input['country'] ?? null)),
            'standardized' => true,
            'verification' => ['provider' => 'none', 'status' => 'not_verified'],
        ];

        return $std;
    }
}
