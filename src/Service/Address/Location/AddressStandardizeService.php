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
    private Env $env;

    public function __construct(Env $env)
    {
        $this->env = $env;
    }

    public function standardize(array $input): array
    {
        $std = [
            'line1' => (string) ($input['line1'] ?? ''),
            'line2' => (string) ($input['line2'] ?? ''),
            'city' => (string) ($input['city'] ?? ''),
            'region' => (string) ($input['region'] ?? ''),
            'postal' => strtoupper((string) ($input['postal'] ?? '')),
            'country' => strtoupper((string) ($input['country'] ?? '')),
            'standardized' => true,
            'verification' => ['provider' => 'none', 'status' => 'not_verified'],
        ];

        return $std;
    }
}
