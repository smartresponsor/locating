<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Http;

interface AddressReverseHttpBackendInterface
{
    /** @return array<string,mixed> */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array;
}
