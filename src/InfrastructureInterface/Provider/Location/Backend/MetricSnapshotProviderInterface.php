<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Backend;

interface MetricSnapshotProviderInterface
{
    /** @return array<string, array<string, float|int>> */
    public function snapshot(): array;
}
