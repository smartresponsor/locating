<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

interface AddressSuggestionSourceOrderInterface
{
    /**
     * @param iterable<AddressSuggestionSourceInterface> $sources
     *
     * @return AddressSuggestionSourceInterface[]
     */
    public function order(iterable $sources, string $query, ?string $countryCode = null, int $limit = 5): array;
}
