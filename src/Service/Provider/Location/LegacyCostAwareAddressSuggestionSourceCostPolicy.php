<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\ServiceInterface\Provider\Location\AddressSuggestionSourceCostPolicyInterface;
use App\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;

final class LegacyCostAwareAddressSuggestionSourceCostPolicy implements AddressSuggestionSourceCostPolicyInterface
{
    public function __construct(private readonly ProviderCostSignalReaderInterface $signalReader)
    {
    }

    public function penalty(string $sourceKey, string $query, ?string $countryCode = null, int $limit = 5): float
    {
        $signal = $this->signalReader->read($sourceKey, 'suggest', [
            'countryCode' => $countryCode,
            'limit' => $limit,
        ]);

        return min(1.0, max(0.0, $signal->unitCost()));
    }
}
