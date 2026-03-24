<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use App\Bridge\Legacy\Entity\Location\AddressSuggestionLegacyInterface;

interface AddressSuggestRankerInterface
{
    /**
     * @param AddressSuggestionLegacyInterface[] $items
     *
     * @return AddressSuggestionLegacyInterface[]
     */
    public function rank(string $query, ?string $countryCode, ?string $locale, array $items): array;
}
