<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\ServiceInterface\Provider\Location\AddressSuggestionSourceHealthPolicyInterface;
use App\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;

final class LegacyHealthAwareAddressSuggestionSourceHealthPolicy implements AddressSuggestionSourceHealthPolicyInterface
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
