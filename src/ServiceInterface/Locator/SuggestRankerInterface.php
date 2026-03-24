<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use App\Bridge\Legacy\Entity\Location\AddressSuggestionLegacyInterface;

interface SuggestRankerInterface
{
    /**
     * @param AddressSuggestionLegacyInterface[] $suggestList
     *
     * @return AddressSuggestionLegacyInterface[]
     */
    public function rank(string $query, array $suggestList, ?string $countryCode): array;
}
