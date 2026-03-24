<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

use App\EntityInterface\AddressSuggestionInterface;

/**
 * Rank suggestions according to fuzzy match and locale/country hints.
 */
interface AddressSuggestRankerInterface
{
    /**
     * @param AddressSuggestionInterface[] $items
     * @return AddressSuggestionInterface[]
     */
    public function rank(string $query, ?string $countryCode, ?string $locale, array $items): array;
}
