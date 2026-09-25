<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Policy\Provider\Location;

use App\Locating\PolicyInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;

final class QuotaAwareAddressReverseSourceQuotaPolicy implements AddressReverseSourceQuotaPolicyInterface
{
    public function __construct(private readonly ProviderQuotaSignalReaderInterface $signalReader)
    {
    }

    public function allows(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): bool
    {
        return $this->signalReader->read($sourceKey, 'reverse', [
            'latitude' => $latitude,
            'longitude' => $longitude,
            'countryCode' => $countryCode,
            'units' => 1,
        ])->allowed();
    }
}
