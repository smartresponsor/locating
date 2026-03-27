<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionRankerInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceOrderInterface;

final class OrderedAddressSuggestionProvider implements AddressSuggestionProviderInterface
{
    /** @var AddressSuggestionSourceInterface[] */
    private array $sources;

    /**
     * @param iterable<AddressSuggestionSourceInterface> $sources
     */
    public function __construct(
        iterable $sources,
        private readonly AddressSuggestionSourceOrderInterface $sourceOrder,
        private readonly AddressSuggestionRankerInterface $ranker,
    ) {
        $this->sources = [];
        foreach ($sources as $source) {
            if ($source instanceof AddressSuggestionSourceInterface) {
                $this->sources[] = $source;
            }
        }
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        if ('' === $query || $limit <= 0) {
            return [];
        }

        $items = [];
        foreach ($this->sourceOrder->order($this->sources, $query, $countryCode, $limit) as $source) {
            foreach ($source->suggest($query, $countryCode, $limit) as $item) {
                $items[] = $item;
            }
        }

        $ranked = $this->ranker->rank($query, $countryCode, $items);
        if (count($ranked) <= $limit) {
            return $ranked;
        }

        return array_slice($ranked, 0, $limit);
    }
}
