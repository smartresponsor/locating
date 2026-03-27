<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\LocationStatusReportInterface;
use App\EntityInterface\Location\ProviderGovernanceSnapshotInterface;
use App\EntityInterface\Location\ProviderMetricSnapshotInterface;

final class LocationStatusReport implements LocationStatusReportInterface
{
    /**
     * @param array<string, ProviderMetricSnapshotInterface>     $metrics
     * @param array<string, ProviderGovernanceSnapshotInterface> $governance
     */
    public function __construct(
        private readonly string $service,
        private readonly string $status,
        private readonly array $metrics,
        private readonly array $governance = [],
    ) {
    }

    public function service(): string
    {
        return $this->service;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function metrics(): array
    {
        return $this->metrics;
    }

    public function governance(): array
    {
        return $this->governance;
    }

    public function toArray(): array
    {
        $metrics = [];
        foreach ($this->metrics as $operation => $snapshot) {
            $metrics[$operation] = $snapshot->toArray();
        }

        $governance = [];
        foreach ($this->governance as $sourceKey => $snapshot) {
            $governance[$sourceKey] = $snapshot->toArray();
        }

        return [
            'service' => $this->service,
            'status' => $this->status,
            'metrics' => $metrics,
            'governance' => $governance,
        ];
    }
}
