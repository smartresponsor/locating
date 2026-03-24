<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface ForwardAggregatorInterface
{
    public function locate(string $q, ?string $region=null): array;
}