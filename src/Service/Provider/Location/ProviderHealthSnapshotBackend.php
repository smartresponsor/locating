<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\Observability\ProviderHealthMonitorInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSnapshotBackendInterface;

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
