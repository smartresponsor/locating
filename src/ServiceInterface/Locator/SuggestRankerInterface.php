<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface;

interface SuggestRankerInterface
{
    /**
     * @param AddressSuggestionInterface[] $suggestList
     * @return AddressSuggestionInterface[]
     */
    public function rank(string $query, array $suggestList, ?string $countryCode): array;
}

