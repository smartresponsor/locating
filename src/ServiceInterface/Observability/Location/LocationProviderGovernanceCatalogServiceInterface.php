<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceSnapshotInterface;

interface LocationProviderGovernanceCatalogServiceInterface
{
    /**
     * @return array<string, ProviderGovernanceSnapshotInterface>
     */
    public function catalog(): array;
}
