<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Provider\Location;

use App\EntityInterface\Location\ProviderMetricSnapshotInterface;
use App\InfrastructureInterface\Provider\Location\ProviderMetricSnapshotStoreInterface;

final class InMemoryProviderMetricSnapshotStore implements ProviderMetricSnapshotStoreInterface
{
    /**
     * @param array<string, ProviderMetricSnapshotInterface> $snapshots
     */
    public function __construct(private array $snapshots = [])
    {
    }

    public function snapshot(): array
    {
        return $this->snapshots;
    }
}
