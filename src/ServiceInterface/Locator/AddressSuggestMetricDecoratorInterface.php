<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface AddressSuggestMetricDecoratorInterface
{
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}