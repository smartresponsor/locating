<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Http\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionViewInterface;

interface LocationAddressSuggestServiceInterface
{
    /** @return list<AddressSuggestionViewInterface> */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
