<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderHealthLegacyMonitorInterface
{
    public function update(string $provider, bool $ok, float $ms): void;

    /** @return array<string,mixed> */
    public function snapshot(): array;
}
