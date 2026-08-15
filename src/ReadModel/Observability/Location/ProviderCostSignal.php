<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderCostSignalInterface;

final class ProviderCostSignal implements ProviderCostSignalInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $operation,
        private readonly string $region,
        private readonly float $unitCost,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function operation(): string
    {
        return $this->operation;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function unitCost(): float
    {
        return $this->unitCost;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'operation' => $this->operation,
            'region' => $this->region,
            'unitCost' => $this->unitCost,
        ];
    }
}
