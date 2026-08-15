<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceSnapshotInterface;

final class ProviderGovernanceSnapshot implements ProviderGovernanceSnapshotInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $operation,
        private readonly float $successRate,
        private readonly float $ewmaMs,
        private readonly bool $quotaAllowed,
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

    public function successRate(): float
    {
        return $this->successRate;
    }

    public function ewmaMs(): float
    {
        return $this->ewmaMs;
    }

    public function quotaAllowed(): bool
    {
        return $this->quotaAllowed;
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
            'successRate' => $this->successRate,
            'ewmaMs' => $this->ewmaMs,
            'quotaAllowed' => $this->quotaAllowed,
            'unitCost' => $this->unitCost,
        ];
    }
}
