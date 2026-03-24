<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressBatchJob;
use Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface;
use Smartresponsor\EntityInterface\Locator\AddressResultInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface;
use Smartresponsor\InfrastructureInterface\Locator\AddressBatchResultStorageInterface;
use Smartresponsor\Message\Locator\AddressBatchMessage;
use Smartresponsor\ServiceInterface\Locator\LocationAddressBatchServiceInterface;

final class LocationAddressBatchService implements LocationAddressBatchServiceInterface
{
    public function __construct(
        private AddressBatchJobRepositoryInterface $jobRepository,
        private AddressBatchMessageBusInterface $messageBus,
        private AddressBatchResultStorageInterface $resultStorage
    ) {
    }

    public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface
    {
        $jobId = bin2hex(random_bytes(16));
        $job = new AddressBatchJob($jobId, $tenantId, count($itemList));
        $this->jobRepository->save($job);

        foreach ($itemList as $item) {
            if (!is_array($item)) {
                continue;
            }
            $payload = ['raw' => (string)($item['raw'] ?? ''), 'data' => (array)($item['data'] ?? [])];
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
        return array_map(
            static fn(AddressResultInterface $result): array => $result->toArray(),
            $this->resultStorage->resultList($jobId)
        );
    }
}
