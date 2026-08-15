<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Batch\Location;

interface AddressBatchResultBackendInterface
{
    /**
     * @param array<string,mixed>|null                             $address
     * @param list<array{field:string,code:string,message:string}> $issues
     */
    public function create(string $status, ?array $address, array $issues): AddressBatchResultRecordInterface;
}
