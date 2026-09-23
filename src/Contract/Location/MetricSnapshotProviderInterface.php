<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface MetricSnapshotProviderInterface
{
    /** @return array<string, array<string, float|int>> */
    public function snapshot(): array;
}
