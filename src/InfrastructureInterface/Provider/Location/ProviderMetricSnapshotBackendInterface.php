<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Provider\Location;

interface ProviderMetricSnapshotBackendInterface
{
    /** @return array<string,array<string,float|int>> */
    public function snapshot(): array;
}
