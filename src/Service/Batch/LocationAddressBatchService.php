<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Batch\Location;

use App\EntityInterface\Location\AddressBatchJobInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobStoreInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchMessageDispatcherInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\Message\Batch\Location\AddressBatchMessage;
use App\ServiceInterface\Batch\Location\LocationAddressBatchServiceInterface;

final class LocationAddressBatchService implements LocationAddressBatchServiceInterface
{
    public function __construct(
        private AddressBatchJobStoreInterface $jobStore,
        private AddressBatchMessageDispatcherInterface $messageDispatcher,
        private AddressBatchResultReaderInterface $resultReader,
    ) {
    }

    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface
    {
        $job = $this->jobStore->create($tenantId, count($itemList));
        $jobId = $job->jobId();

        foreach ($itemList as $item) {
            if (!is_array($item)) {
                continue;
            }

            $payload = [
                'raw' => (string) ($item['raw'] ?? ''),
                'data' => (array) ($item['data'] ?? []),
            ];

            $this->messageDispatcher->dispatch(new AddressBatchMessage($jobId, $payload));
        }

        return $job;
    }

    public function jobStatus(string $jobId): ?AddressBatchJobInterface
    {
        return $this->jobStore->find($jobId);
    }

    public function jobResultList(string $jobId): array
    {
        return $this->resultReader->resultList($jobId);
    }
}
