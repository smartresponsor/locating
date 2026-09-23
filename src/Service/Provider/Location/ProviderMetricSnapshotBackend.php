<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location;

use App\Locating\Contract\Location\MetricSnapshotProviderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderMetricSnapshotBackendInterface;

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
