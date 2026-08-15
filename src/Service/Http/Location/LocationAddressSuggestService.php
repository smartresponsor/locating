<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;
use App\Locating\ServiceInterface\Http\Location\LocationAddressSuggestServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationViewFactoryInterface;

final class LocationAddressSuggestService implements LocationAddressSuggestServiceInterface
{
    public function __construct(
        private readonly AddressSuggestCapabilityInterface $inner,
        private readonly LocationViewFactoryInterface $viewFactory,
    ) {
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        $items = [];

        foreach ($this->inner->suggest($query, $countryCode, $limit) as $suggestion) {
            $items[] = $this->viewFactory->createSuggestionView($suggestion);
        }

        return $items;
    }
}
