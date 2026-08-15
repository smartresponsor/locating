<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\Locating\ModelInterface\Location\AddressReverseResultInterface;

final class AddressBatchResultRecord implements AddressBatchResultRecordInterface
{
    public function __construct(private readonly AddressReverseResultInterface $inner)
    {
    }

    public function inner(): AddressReverseResultInterface
    {
        return $this->inner;
    }

    public function toArray(): array
    {
        return $this->inner->toArray();
    }
}
