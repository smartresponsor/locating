<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ReadModel\Observability\Location\ProviderMetricSnapshot;
use App\Locating\ServiceInterface\Provider\Location\ProviderMetricSnapshotBackendInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderMetricSnapshotStoreInterface;

final class ProviderMetricSnapshotStore implements ProviderMetricSnapshotStoreInterface
{
    public function __construct(private readonly ProviderMetricSnapshotBackendInterface $backend)
    {
    }

    public function snapshot(): array
    {
        $raw = $this->backend->snapshot();
        $snapshots = [];

        foreach ($raw as $operation => $metric) {
            $operationName = (string) $operation;
            $snapshots[$operationName] = new ProviderMetricSnapshot(
                $operationName,
                (int) ($metric['count'] ?? 0),
                (int) ($metric['errorCount'] ?? 0),
                (float) ($metric['avgMs'] ?? 0.0),
                (float) ($metric['errorRate'] ?? 0.0),
            );
        }

        return $snapshots;
    }
}
