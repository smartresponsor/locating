<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface;

final class AddressSuggestCapability implements AddressSuggestCapabilityInterface
{
    public function __construct(private readonly AddressSuggestionProviderInterface $provider)
    {
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        return $this->provider->suggest($query, $countryCode, $limit);
    }
}
