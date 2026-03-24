<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\InfrastructureInterface;

use App\EntityInterface\AddressSuggestionInterface;

interface AddressSuggestProviderInterface
{
    /**
     * @return AddressSuggestionInterface[]
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
