<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Address\Location;

use App\EntityInterface\Location\AddressSuggestionResultInterface;

interface AddressSuggestCapabilityInterface
{
    /**
     * @return AddressSuggestionResultInterface[]
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
