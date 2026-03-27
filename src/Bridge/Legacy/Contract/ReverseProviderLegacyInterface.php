<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Contract\Location;

interface ReverseProviderLegacyInterface
{
    public function name(): string;

    /** @return array<string,mixed> */
    public function reverse(float $lat, float $lon): array;
}
