<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Observability\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceSnapshot;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;

final class LocationProviderGovernanceCatalogService implements LocationProviderGovernanceCatalogServiceInterface
{
    /**
     * @param array<string, string> $sourceOperations
     */
    public function __construct(
        private readonly ProviderHealthSignalReaderInterface $healthSignalReader,
        private readonly ProviderQuotaSignalReaderInterface $quotaSignalReader,
        private readonly ProviderCostSignalReaderInterface $costSignalReader,
        private readonly array $sourceOperations,
    ) {
    }

    public function catalog(): array
    {
        $catalog = [];

        foreach ($this->sourceOperations as $sourceKey => $operation) {
            $health = $this->healthSignalReader->read($sourceKey);
            $quota = $this->quotaSignalReader->read($sourceKey, $operation);
            $cost = $this->costSignalReader->read($sourceKey, $operation);

            $catalog[$sourceKey] = new ProviderGovernanceSnapshot(
                $sourceKey,
                $operation,
                $health->successRate(),
                $health->ewmaMs(),
                $quota->allowed(),
                $cost->unitCost(),
            );
        }

        return $catalog;
    }
}
