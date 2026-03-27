<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Provider\Location;

interface AddressReverseHttpBackendInterface
{
    /** @return array<string,mixed> */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array;
}
