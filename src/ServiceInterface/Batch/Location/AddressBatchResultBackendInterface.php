<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchResultRecordInterface;

interface AddressBatchResultBackendInterface
{
    /**
     * @param array<string,mixed>|null                             $address
     * @param list<array{field:string,code:string,message:string}> $issues
     */
    public function create(string $status, ?array $address, array $issues): AddressBatchResultRecordInterface;
}
