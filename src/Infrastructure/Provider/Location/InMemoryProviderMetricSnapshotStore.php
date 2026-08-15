<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderMetricSnapshotStoreInterface;
use App\Locating\ModelInterface\Location\ProviderMetricSnapshotInterface;

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
