<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Observability\Location;

use App\Entity\Location\ProviderGovernanceSnapshot;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;
use App\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;

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
