<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

interface AddressSuggestionSourceCostPolicyInterface
{
    public function penalty(string $sourceKey, string $query, ?string $countryCode = null, int $limit = 5): float;
}
