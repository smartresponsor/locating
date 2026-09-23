<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Provider\Location;

interface AddressSuggestGatewayInterface
{
    /**
     * @return list<array{label:string,address:array<string,mixed>,providerKey:string}>
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
