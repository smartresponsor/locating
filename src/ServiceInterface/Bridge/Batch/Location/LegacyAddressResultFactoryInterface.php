<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Bridge\Batch\Location;

use App\EntityInterface\Location\AddressPipelineResultInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;

interface LegacyAddressResultFactoryInterface
{
    public function create(AddressPipelineResultInterface $result): AddressBatchResultRecordInterface;
}
