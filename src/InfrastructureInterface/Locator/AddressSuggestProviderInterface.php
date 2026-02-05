<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

use Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface;

interface AddressSuggestProviderInterface
{
    /**
     * @return AddressSuggestionInterface[]
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
