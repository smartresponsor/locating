<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressSuggestionInterface;

interface SuggestRankerInterface
{
    /**
     * @param AddressSuggestionInterface[] $suggestList
     * @return AddressSuggestionInterface[]
     */
    public function rank(string $query, array $suggestList, ?string $countryCode): array;
}

