<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderHealthSnapshotBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderHealthSnapshotStoreInterface;

final class ProviderHealthSnapshotStore implements ProviderHealthSnapshotStoreInterface
{
    public function __construct(private readonly ProviderHealthSnapshotBackendInterface $backend)
    {
    }

    public function snapshot(): array
    {
        return $this->backend->snapshot();
    }
}
