<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Backend;

interface AddressSuggestBackendInterface
{
    /**
     * @return list<array{label:string,address:array<string,mixed>,providerKey:string}>
     */
    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array;
}
