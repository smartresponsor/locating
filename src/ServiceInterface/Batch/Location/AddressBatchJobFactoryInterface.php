<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use App\Locating\ModelInterface\Location\AddressBatchJobInterface;

interface AddressBatchJobFactoryInterface
{
    public function create(AddressBatchJobRecordInterface $job): AddressBatchJobInterface;
}
