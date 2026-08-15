<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface ReverseLocationProviderContract
{
    public function nameEntity(): string;

    /** @return array<string,mixed> */
    public function reverse(float $lat, float $lon): array;
}
