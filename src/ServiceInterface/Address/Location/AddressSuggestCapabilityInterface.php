<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Address\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;

interface AddressSuggestCapabilityInterface
{
    /**
     * @return AddressSuggestionResultInterface[]
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
