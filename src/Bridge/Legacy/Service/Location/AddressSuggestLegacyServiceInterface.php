<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

use App\Bridge\Legacy\Entity\Location\AddressSuggestionLegacyInterface;

interface AddressSuggestLegacyServiceInterface
{
    /**
     * @return AddressSuggestionLegacyInterface[]
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
