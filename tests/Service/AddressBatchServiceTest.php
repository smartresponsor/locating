<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Tests\Service;

use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressResult;
use Smartresponsor\Entity\AddressStatus;
use Smartresponsor\EntityInterface\AddressBatchJobInterface;
use Smartresponsor\Infrastructure\InMemoryAddressBatchJobRepository;
use Smartresponsor\Infrastructure\InMemoryAddressBatchMessageBus;
use Smartresponsor\Infrastructure\InMemoryAddressBatchResultStorage;
use Smartresponsor\MessageHandler\AddressBatchMessageHandler;
use Smartresponsor\Service\LocationAddressBatchService;
use Smartresponsor\ServiceInterface\AddressPipelineInterface;

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
