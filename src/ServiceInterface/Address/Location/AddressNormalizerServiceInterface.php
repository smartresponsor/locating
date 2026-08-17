<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Address\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AddressNormalizerServiceInterface
{
    /**
     * @param array<string, mixed> $raw
     * @return array{address:\App\Locating\Model\Location\CanonicalAddress, score:float}
     */
    public function canonicalize(array $raw, string $provider): array;
}
