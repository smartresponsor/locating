<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\AddressReverseHttpBackendInterface;

final class SmartresponsorAddressReverseHttpBackend implements AddressReverseHttpBackendInterface
{
    public function __construct(private readonly ReverseHttpClientInterface $client)
    {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
    {
        return $this->client->reverse($latitude, $longitude, $countryCode);
    }
}
