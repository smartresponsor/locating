<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\InfrastructureInterface\Provider\Location;

interface ProviderHealthSnapshotStoreInterface
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public function snapshot(): array;
}
