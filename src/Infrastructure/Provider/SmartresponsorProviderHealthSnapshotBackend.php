<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderHealthLegacyMonitorInterface;
use App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotBackendInterface;

final class SmartresponsorProviderHealthSnapshotBackend implements ProviderHealthSnapshotBackendInterface
{
    public function __construct(private readonly ProviderHealthLegacyMonitorInterface $healthMonitor)
    {
    }

    public function snapshot(): array
    {
        return $this->healthMonitor->snapshot();
    }
}
