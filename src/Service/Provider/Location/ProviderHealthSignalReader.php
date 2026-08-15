<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderHealthSnapshotStoreInterface;
use App\Locating\Model\Location\ProviderHealthSignal;
use App\Locating\ModelInterface\Location\ProviderHealthSignalInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;

final class ProviderHealthSignalReader implements ProviderHealthSignalReaderInterface
{
    public function __construct(private readonly ProviderHealthSnapshotStoreInterface $snapshotStore)
    {
    }

    public function read(string $sourceKey): ProviderHealthSignalInterface
    {
        $snapshot = $this->snapshotStore->snapshot();
        $row = $snapshot[$sourceKey] ?? null;

        return new ProviderHealthSignal(
            $sourceKey,
            is_array($row) && isset($row['successRate']) ? (float) $row['successRate'] : 0.5,
            is_array($row) && isset($row['ewmaMs']) ? (float) $row['ewmaMs'] : 500.0,
        );
    }
}
