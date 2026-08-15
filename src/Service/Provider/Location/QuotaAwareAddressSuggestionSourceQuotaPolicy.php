<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceQuotaPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;

final class QuotaAwareAddressSuggestionSourceQuotaPolicy implements AddressSuggestionSourceQuotaPolicyInterface
{
    public function __construct(private readonly ProviderQuotaSignalReaderInterface $signalReader)
    {
    }

    public function allows(string $sourceKey, string $query, ?string $countryCode = null, int $limit = 5): bool
    {
        if ($limit <= 0 || '' === $query) {
            return false;
        }

        return $this->signalReader->read($sourceKey, 'geocode', [
            'query' => $query,
            'countryCode' => $countryCode,
            'limit' => $limit,
            'units' => 1,
        ])->allowed();
    }
}
