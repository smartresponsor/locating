<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Provider\Location;

use App\InfrastructureInterface\Provider\Location\AddressSuggestBackendInterface;
use App\InfrastructureInterface\Provider\Location\AddressSuggestGatewayInterface;

final class LegacyAddressSuggestGateway implements AddressSuggestGatewayInterface
{
    public function __construct(private readonly AddressSuggestBackendInterface $backend)
    {
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        return $this->backend->suggest($query, $countryCode, $limit);
    }
}
