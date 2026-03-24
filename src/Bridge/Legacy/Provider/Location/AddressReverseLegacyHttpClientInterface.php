<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface AddressReverseLegacyHttpClientInterface
{
    /**
     * @return array<mixed>
     */
    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array;
}
