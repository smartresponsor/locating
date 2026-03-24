<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Entity\Location\AddressSuggestionLegacyInterface;
use Smartresponsor\Entity\Locator\AddressSuggestion;
use Smartresponsor\ServiceInterface\Locator\SuggestRankerInterface;

final class SuggestRanker implements SuggestRankerInterface
{
    /**
     * @param AddressSuggestionLegacyInterface[] $suggestList
     *
     * @return AddressSuggestionLegacyInterface[]
     */
    public function rank(string $query, array $suggestList, ?string $countryCode): array
    {
        $normalizedQuery = mb_strtolower(trim($query));
        if ('' === $normalizedQuery) {
            return $suggestList;
        }

        $scoredList = [];
        foreach ($suggestList as $suggest) {
            if (!$suggest instanceof AddressSuggestion) {
                continue;
            }
            $scoredList[] = $suggest;
        }

        usort($scoredList, static fn (AddressSuggestion $left, AddressSuggestion $right): int => strcmp($left->label(), $right->label()));

        return $scoredList;
    }
}
