<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Entity\AddressInput;
use App\Entity\AddressResult;
use App\Entity\AddressStatus;
use App\EntityInterface\AddressBatchJobInterface;
use App\Infrastructure\InMemoryAddressBatchJobRepository;
use App\Infrastructure\InMemoryAddressBatchMessageBus;
use App\Infrastructure\InMemoryAddressBatchResultStorage;
use App\MessageHandler\AddressBatchMessageHandler;
use App\Service\LocationAddressBatchService;
use App\ServiceInterface\AddressPipelineInterface;

final class LocationAddressBatchServiceTest extends TestCase
{
    public function testCreateJobAndProcessItemsWithInMemoryInfrastructure(): void
    {
        $jobRepository = new InMemoryAddressBatchJobRepository();
        $resultStorage = new InMemoryAddressBatchResultStorage();

        $pipeline = new class implements AddressPipelineInterface {
            public function process(AddressInput $input): AddressResult
            {
                return AddressResult::create(AddressStatus::VERIFIED, null);
            }
        };

        $handler = new AddressBatchMessageHandler($pipeline, $jobRepository, $resultStorage);
        $messageBus = new InMemoryAddressBatchMessageBus($handler);

        $service = new LocationAddressBatchService($jobRepository, $messageBus, $resultStorage);

        $items = [
            ['raw' => 'foo street', 'data' => ['country' => 'US']],
            ['raw' => 'bar avenue', 'data' => ['country' => 'US']],
        ];

        $job = $service->createJob('tenant-demo', $items);

        self::assertInstanceOf(AddressBatchJobInterface::class, $job);
        self::assertSame(2, $job->totalCount());
        self::assertSame(2, $job->processedCount());

        $results = $service->jobResultList($job->jobId());
        self::assertCount(2, $results);
    }
}
