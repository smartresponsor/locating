<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\AddressSuggestBackendInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestGatewayInterface;

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
