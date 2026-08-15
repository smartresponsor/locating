<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceOrderInterface;

final class StaticAddressSuggestionSourceOrder implements AddressSuggestionSourceOrderInterface
{
    public function order(iterable $sources, string $query, ?string $countryCode = null, int $limit = 5): array
    {
        $ordered = [];

        foreach ($sources as $source) {
            if ($source instanceof AddressSuggestionSourceInterface) {
                $ordered[] = $source;
            }
        }

        return $ordered;
    }
}
