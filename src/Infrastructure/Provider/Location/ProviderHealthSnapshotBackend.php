<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderHealthSnapshotBackendInterface;
use App\Locating\ServiceInterface\Provider\Location\Observability\ProviderHealthMonitorInterface;

final class ProviderHealthSnapshotBackend implements ProviderHealthSnapshotBackendInterface
{
    public function __construct(private readonly ProviderHealthMonitorInterface $healthMonitor)
    {
    }

    public function snapshot(): array
    {
        return $this->healthMonitor->snapshot();
    }
}
