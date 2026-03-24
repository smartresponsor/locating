<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressSuggestionInterface;

interface AddressSuggestInterface
{
    /**
     * @return AddressSuggestionInterface[]
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
