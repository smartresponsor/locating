<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Policy\Provider\Location;

use App\Locating\PolicyInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;

final class HealthAwareAddressReverseSourceHealthPolicy implements AddressReverseSourceHealthPolicyInterface
{
    public function __construct(private readonly ProviderHealthSignalReaderInterface $signalReader)
    {
    }

    public function score(string $sourceKey): float
    {
        $signal = $this->signalReader->read($sourceKey);
        $latencyPenalty = min(0.5, max(0.0, $signal->ewmaMs() / 2000.0));

        return max(0.0, min(1.0, $signal->successRate() - $latencyPenalty + 0.25));
    }
}
