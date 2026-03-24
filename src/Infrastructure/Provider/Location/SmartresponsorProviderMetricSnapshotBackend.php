<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderMetricSnapshotBackendInterface;

final class SmartresponsorProviderMetricSnapshotBackend implements ProviderMetricSnapshotBackendInterface
{
    public function __construct(private readonly MetricSnapshotProviderInterface $snapshotProvider)
    {
    }

    public function snapshot(): array
    {
        return $this->snapshotProvider->snapshot();
    }
}
