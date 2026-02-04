<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Service\Locator;

use App.Entity\Locator\AddressBatchJob;
use App.EntityInterface\Locator\AddressBatchJobInterface;
use App.EntityInterface\Locator\AddressResultInterface;
use App.InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface;
use App.InfrastructureInterface\Locator\AddressBatchMessageBusInterface;
use App.InfrastructureInterface\Locator\AddressBatchResultStorageInterface;
use App.Message\Locator\AddressBatchMessage;
use App.ServiceInterface\Locator\AddressBatchServiceInterface;

/**
 * High-level entry point for address batch jobs.
 */
final class AddressBatchService implements AddressBatchServiceInterface
{
    public function __construct(
        private AddressBatchJobRepositoryInterface $jobRepository,
        private AddressBatchMessageBusInterface $messageBus,
        private AddressBatchResultStorageInterface $resultStorage
    ) {
    }

    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface
    {
        $total = count($itemList);
        $jobId = bin2hex(random_bytes(16));

        $job = new AddressBatchJob($jobId, $tenantId, $total);
        $this->jobRepository->save($job);

        foreach ($itemList as $item) {
            if (!is_array($item)) {
                continue;
            }

            $payload = [
                'raw' => (string)($item['raw'] ?? ''),
                'data' => (array)($item['data'] ?? []),
            ];

            $this->messageBus->dispatch(new AddressBatchMessage($jobId, $payload));
        }

        return $job;
    }

    public function jobStatus(string $jobId): ?AddressBatchJobInterface
    {
        return $this->jobRepository->find($jobId);
    }

    public function jobResultList(string $jobId): array
    {
        $resultList = $this->resultStorage->resultList($jobId);

        return array_map(
            static function (AddressResultInterface $result): array {
                return $result->toArray();
            },
            $resultList
        );
    }
}
