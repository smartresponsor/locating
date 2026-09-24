<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ProviderInterface\Location\AddressSuggestionProviderInterface;
use App\Locating\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;

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
