<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface;
use App\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;

final class LegacyCostAwareAddressReverseSourceCostPolicy implements AddressReverseSourceCostPolicyInterface
{
    public function __construct(private readonly ProviderCostSignalReaderInterface $signalReader)
    {
    }

    public function penalty(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): float
    {
        $signal = $this->signalReader->read($sourceKey, 'reverse', [
            'countryCode' => $countryCode,
            'latitude' => $latitude,
            'longitude' => $longitude,
        ]);

        return min(1.0, max(0.0, $signal->unitCost()));
    }
}
