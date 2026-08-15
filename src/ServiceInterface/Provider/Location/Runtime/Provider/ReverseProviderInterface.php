<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Provider;

interface ReverseProviderInterface
{
    public function nameEntity(): string;

    /** @return array<string,mixed> */
    public function reverse(float $lat, float $lon): array;
}
