<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Batch\Location;

interface AddressBatchResultRecordInterface
{
    /** @return array<string,mixed> */
    public function toArray(): array;
}
