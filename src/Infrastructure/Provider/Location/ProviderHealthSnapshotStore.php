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
        $result = [];
        foreach ($this->backend->snapshot() as $provider => $snapshot) {
            if (!is_array($snapshot)) {
                continue;
            }
            $normalized = [];
            foreach ($snapshot as $key => $value) {
                if (is_string($key)) {
                    $normalized[$key] = $value;
                }
            }
            $result[$provider] = $normalized;
        }

        return $result;
    }
}
