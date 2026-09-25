<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\PolicyInterface\Provider\Location;

interface AddressSuggestionSourceQuotaPolicyInterface
{
    public function allows(string $sourceKey, string $query, ?string $countryCode = null, int $limit = 5): bool;
}
