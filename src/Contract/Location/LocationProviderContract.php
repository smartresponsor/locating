<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface LocationProviderContract
{
    public function nameEntity(): string;

    /** @return array<string,mixed> */
    public function geocode(string $q): array;
}
