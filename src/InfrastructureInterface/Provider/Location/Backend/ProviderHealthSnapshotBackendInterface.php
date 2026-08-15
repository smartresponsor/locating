<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Backend;

interface ProviderHealthSnapshotBackendInterface
{
    /** @return array<string,mixed> */
    public function snapshot(): array;
}
