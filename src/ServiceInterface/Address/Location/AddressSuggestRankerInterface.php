<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\ServiceInterface\Address\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;

interface AddressSuggestRankerInterface
{
    /**
     * @param AddressSuggestionResultInterface[] $items
     *
     * @return AddressSuggestionResultInterface[]
     */
    public function rank(string $query, ?string $countryCode, ?string $locale, array $items): array;
}
