<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderMetricSnapshotInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderMetricSnapshotStoreInterface;

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
