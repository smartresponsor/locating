<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Tests\Locator\Service;

use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\Entity\Locator\AddressResult;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\EntityInterface\Locator\AddressBatchJobInterface;
use Smartresponsor\Infrastructure\Locator\InMemoryAddressBatchJobRepository;
use Smartresponsor\Infrastructure\Locator\InMemoryAddressBatchMessageBus;
use Smartresponsor\Infrastructure\Locator\InMemoryAddressBatchResultStorage;
use Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler;
use Smartresponsor\Service\Locator\LocationAddressBatchService;
use Smartresponsor\ServiceInterface\Locator\AddressPipelineInterface;

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
