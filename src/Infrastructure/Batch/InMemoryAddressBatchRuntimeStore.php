<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\Entity\Location\AddressBatchJob;
use App\Entity\Location\AddressBatchJobStatus;
use App\EntityInterface\Location\AddressBatchJobInterface;
use App\EntityInterface\Location\AddressPipelineResultInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobStoreInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface;

final class InMemoryAddressBatchRuntimeStore implements AddressBatchJobStoreInterface, AddressBatchJobProgressWriterInterface, AddressBatchResultReaderInterface, AddressBatchResultWriterInterface
{
    /** @var array<string,array{tenantId:string,status:AddressBatchJobStatus,totalCount:int,processedCount:int,createdAt:\DateTimeImmutable,updatedAt:\DateTimeImmutable}> */
    private array $jobMap = [];

    /** @var array<string,list<array<string,mixed>>> */
    private array $resultMap = [];

    public function create(string $tenantId, int $totalCount): AddressBatchJobInterface
    {
        $jobId = bin2hex(random_bytes(16));
        $now = new \DateTimeImmutable();

        $this->jobMap[$jobId] = [
            'tenantId' => $tenantId,
            'status' => AddressBatchJobStatus::PENDING,
            'totalCount' => $totalCount,
            'processedCount' => 0,
            'createdAt' => $now,
            'updatedAt' => $now,
        ];
        $this->resultMap[$jobId] = [];

        return $this->hydrateJob($jobId);
    }

    public function find(string $jobId): ?AddressBatchJobInterface
    {
        if (!isset($this->jobMap[$jobId])) {
            return null;
        }

        return $this->hydrateJob($jobId);
    }

    public function markRun(string $jobId): bool
    {
        if (!isset($this->jobMap[$jobId])) {
            return false;
        }

        $job = $this->jobMap[$jobId];
        if (AddressBatchJobStatus::PENDING === $job['status']) {
            $job['status'] = AddressBatchJobStatus::RUNNING;
            $job['updatedAt'] = new \DateTimeImmutable();
            $this->jobMap[$jobId] = $job;
        }

        return true;
    }

    public function incrementProcessed(string $jobId): bool
    {
        if (!isset($this->jobMap[$jobId])) {
            return false;
        }

        $job = $this->jobMap[$jobId];
        ++$job['processedCount'];
        $job['status'] = $job['processedCount'] >= $job['totalCount']
            ? AddressBatchJobStatus::COMPLETED
            : AddressBatchJobStatus::RUNNING;
        $job['updatedAt'] = new \DateTimeImmutable();
        $this->jobMap[$jobId] = $job;

        return true;
    }

    public function appendPipelineResult(string $jobId, AddressPipelineResultInterface $result): void
    {
        if (!isset($this->resultMap[$jobId])) {
            $this->resultMap[$jobId] = [];
        }

        $this->resultMap[$jobId][] = $result->toArray();
    }

    public function resultList(string $jobId): array
    {
        return $this->resultMap[$jobId] ?? [];
    }

    private function hydrateJob(string $jobId): AddressBatchJobInterface
    {
        $job = $this->jobMap[$jobId];

        return new AddressBatchJob(
            $jobId,
            $job['tenantId'],
            $job['status'],
            $job['totalCount'],
            $job['processedCount'],
            $job['createdAt'],
            $job['updatedAt'],
        );
    }
}
