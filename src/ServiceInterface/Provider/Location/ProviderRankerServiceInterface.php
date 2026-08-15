<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */
interface ProviderRankerServiceInterface
{
    public static function sort(array $items): array;
}
