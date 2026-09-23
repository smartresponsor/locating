<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderMetricSnapshotInterface;

interface ProviderMetricSnapshotStoreInterface
{
    /**
     * @return array<string, ProviderMetricSnapshotInterface>
     */
    public function snapshot(): array;
}
