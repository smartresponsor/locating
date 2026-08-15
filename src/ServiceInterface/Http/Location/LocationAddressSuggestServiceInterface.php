<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Http\Location;

interface LocationAddressSuggestServiceInterface
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
