<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Gateway\ProviderCostCatalogGatewayInterface;
use App\Locating\ReadModel\Observability\Location\ProviderCostSignal;
use App\Locating\ReadModelInterface\Observability\Location\ProviderCostSignalInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;

final class ProviderCostSignalReader implements ProviderCostSignalReaderInterface
{
    public function __construct(
        private readonly ProviderCostCatalogGatewayInterface $costCatalog,
        private readonly string $defaultRegion = 'global',
    ) {
    }

    public function read(string $sourceKey, string $operation, array $context = []): ProviderCostSignalInterface
    {
        $regionValue = $context['region'] ?? $context['countryCode'] ?? null;
        $region = is_string($regionValue) ? trim($regionValue) : $this->defaultRegion;
        $region = '' !== $region ? strtoupper($region) : $this->defaultRegion;

        return new ProviderCostSignal(
            $sourceKey,
            $operation,
            $region,
            $this->costCatalog->cost($sourceKey, $region, $operation),
        );
    }
}
