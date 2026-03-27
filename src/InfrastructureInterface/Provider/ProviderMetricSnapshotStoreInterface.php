<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\InfrastructureInterface\Provider\Location;

use App\EntityInterface\Location\ProviderMetricSnapshotInterface;

interface ProviderMetricSnapshotStoreInterface
{
    /**
     * @return array<string, ProviderMetricSnapshotInterface>
     */
    public function snapshot(): array;
}
