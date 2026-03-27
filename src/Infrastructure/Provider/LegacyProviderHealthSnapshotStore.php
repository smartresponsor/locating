<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotStoreInterface;

final class LegacyProviderHealthSnapshotStore implements ProviderHealthSnapshotStoreInterface
{
    public function __construct(private readonly ProviderHealthSnapshotBackendInterface $backend)
    {
    }

    public function snapshot(): array
    {
        return $this->backend->snapshot();
    }
}
