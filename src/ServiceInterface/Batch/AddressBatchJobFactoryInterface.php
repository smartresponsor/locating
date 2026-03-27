<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Batch\Location;

use App\EntityInterface\Location\AddressBatchJobInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;

interface AddressBatchJobFactoryInterface
{
    public function create(AddressBatchJobRecordInterface $job): AddressBatchJobInterface;
}
