<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Batch\Location;

use App\Entity\Location\AddressBatchJob;
use App\Entity\Location\AddressBatchJobStatus;
use App\EntityInterface\Location\AddressBatchJobInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordInterface;
use App\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface;

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
