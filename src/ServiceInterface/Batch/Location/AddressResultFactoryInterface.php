<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchResultRecordInterface;
use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;

interface AddressResultFactoryInterface
{
    public function create(AddressPipelineResultInterface $result): AddressBatchResultRecordInterface;
}
