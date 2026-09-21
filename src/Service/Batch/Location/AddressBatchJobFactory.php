<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\Model\Location\AddressBatchJob;
use App\Locating\Model\Location\AddressBatchJobStatus;
use App\Locating\ModelInterface\Location\AddressBatchJobInterface;
use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface;

final class AddressBatchJobFactory implements AddressBatchJobFactoryInterface
{
    public function create(AddressBatchJobRecordInterface $job): AddressBatchJobInterface
    {
        return new AddressBatchJob(
            $job->jobId(),
            $job->tenantId(),
            AddressBatchJobStatus::from($job->jobStatusValue()),
            $job->totalCount(),
            $job->processedCount(),
            $job->createdAt(),
            $job->updatedAt(),
        );
    }
}
