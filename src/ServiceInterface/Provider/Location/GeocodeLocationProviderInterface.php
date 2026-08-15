<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface GeocodeLocationProviderInterface
{
    /** @return array<int,array<string,mixed>> */
    public function geocode(string $q, string $country): array;

    /** @return array<int,array<string,mixed>> */
    public function reverse(float $lat, float $lon): array;

    /** @return array<int,array<string,mixed>> */
    public function autocomplete(string $q, string $country, string $bbox): array;

    public function nameEntity(): string;
}
