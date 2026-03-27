<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderStatAggregatorLegacyInterface
{
    public function observe(string $providerId, string $region, float $latencyMs, bool $ok, float $cost): void;

    /** @return array<string,mixed> */
    public function snapshot(): array;
}
