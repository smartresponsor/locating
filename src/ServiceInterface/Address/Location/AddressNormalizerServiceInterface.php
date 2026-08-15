<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Address\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AddressNormalizerServiceInterface
{
    public function canonicalize(array $raw, string $provider): array;
}
