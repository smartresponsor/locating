<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\MetricSnapshotProviderInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderMetricSnapshotBackendInterface;

final class ProviderMetricSnapshotBackend implements ProviderMetricSnapshotBackendInterface
{
    public function __construct(private readonly MetricSnapshotProviderInterface $snapshotProvider)
    {
    }

    public function snapshot(): array
    {
        return $this->snapshotProvider->snapshot();
    }
}
