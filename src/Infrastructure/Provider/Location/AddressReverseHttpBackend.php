<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Http\AddressReverseHttpBackendInterface;

final class AddressReverseHttpBackend implements AddressReverseHttpBackendInterface
{
    public function __construct(private readonly ReverseHttpClientInterface $client)
    {
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
    {
        return $this->client->reverse($latitude, $longitude, $countryCode);
    }
}
