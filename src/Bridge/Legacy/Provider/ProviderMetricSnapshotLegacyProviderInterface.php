<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderMetricSnapshotLegacyProviderInterface
{
    /**
     * @return array<string,array<string,float|int>>
     */
    public function snapshot(): array;
}
