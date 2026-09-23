<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ReadModel\Observability\Location\ProviderHealthSignal;
use App\Locating\ReadModelInterface\Observability\Location\ProviderHealthSignalInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSnapshotStoreInterface;

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
            is_array($row) && is_numeric($row['successRate'] ?? null) ? (float) $row['successRate'] : 0.5,
            is_array($row) && is_numeric($row['ewmaMs'] ?? null) ? (float) $row['ewmaMs'] : 500.0,
        );
    }
}
