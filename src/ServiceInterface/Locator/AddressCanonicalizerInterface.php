<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AddressCanonicalizerInterface
{
    public function normalize(array $raw, string $locale='en'): array;
}