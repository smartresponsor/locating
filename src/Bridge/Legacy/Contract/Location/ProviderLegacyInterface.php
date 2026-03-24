<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Contract\Location;

interface ProviderLegacyInterface
{
    public function name(): string;

    /** @return array<string,mixed> */
    public function geocode(string $q): array;
}
