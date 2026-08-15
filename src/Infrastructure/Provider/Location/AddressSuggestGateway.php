<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\AddressSuggestBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\AddressSuggestGatewayInterface;

final class AddressSuggestGateway implements AddressSuggestGatewayInterface
{
    public function __construct(private readonly AddressSuggestBackendInterface $backend)
    {
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        return $this->backend->suggest($query, $countryCode, $limit);
    }
}
