<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

use App\EntityInterface\Locator\AddressSuggestionInterface;

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
