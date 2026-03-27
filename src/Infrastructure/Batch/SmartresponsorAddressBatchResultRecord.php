<?php

declare(strict_types=1);

namespace App\Infrastructure\Batch\Location;

use App\Bridge\Legacy\Entity\Location\AddressResultLegacyInterface as LegacyAddressResultInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;

final class SmartresponsorAddressBatchResultRecord implements AddressBatchResultRecordInterface
{
    public function __construct(private readonly LegacyAddressResultInterface $inner)
    {
    }

    public function inner(): LegacyAddressResultInterface
    {
        return $this->inner;
    }

    public function toArray(): array
    {
        return $this->inner->toArray();
    }
}
