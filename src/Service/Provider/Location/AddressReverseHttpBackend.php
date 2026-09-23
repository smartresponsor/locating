<?php

declare(strict_types=1);

namespace App\Locating\Service\Provider\Location;

use App\Locating\Contract\Location\AddressReverseHttpBackendInterface;
use App\Locating\Contract\Location\ReverseHttpClientInterface;

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
