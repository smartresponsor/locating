<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\InfrastructureInterface\Provider\Location\Gateway;

interface AddressSuggestGatewayInterface
{
    /**
     * @return array<int, array{label:string,address:array<string,string>,providerKey:string}>
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
