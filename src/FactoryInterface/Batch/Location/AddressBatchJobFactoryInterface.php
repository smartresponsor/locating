<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\FactoryInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressBatchJobInterface;
use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;

interface AddressBatchJobFactoryInterface
{
    public function create(AddressBatchJobRecordInterface $job): AddressBatchJobInterface;
}
