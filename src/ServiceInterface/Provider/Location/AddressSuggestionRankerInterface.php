<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;

interface AddressSuggestionRankerInterface
{
    /**
     * @param AddressSuggestionResultInterface[] $items
     *
     * @return AddressSuggestionResultInterface[]
     */
    public function rank(string $query, ?string $countryCode, array $items): array;
}
