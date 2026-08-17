<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchJobStoreInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageDispatcherInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\Locating\Message\Batch\Location\AddressBatchMessage;
use App\Locating\ModelInterface\Location\AddressBatchJobInterface;
use App\Locating\ServiceInterface\Batch\Location\LocationAddressBatchServiceInterface;

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
            $raw = is_string($item['raw'] ?? null) ? $item['raw'] : '';
            $data = [];
            if (is_array($item['data'] ?? null)) {
                foreach ($item['data'] as $key => $value) {
                    if (is_string($key)) {
                        $data[$key] = $value;
                    }
                }
            }
            $this->messageDispatcher->dispatch(new AddressBatchMessage($jobId, [
                'raw' => $raw,
                'data' => $data,
            ]));
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
