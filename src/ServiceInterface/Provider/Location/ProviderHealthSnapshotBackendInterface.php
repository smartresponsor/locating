<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface ProviderHealthSnapshotBackendInterface
{
    /** @return array<string,mixed> */
    public function snapshot(): array;
}
