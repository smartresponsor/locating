<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Tests\Locator\Service;

use App\Entity\Locator\AddressInput;
use App\Entity\Locator\AddressResult;
use App\Entity\Locator\AddressStatus;
use App\EntityInterface\Locator\AddressBatchJobInterface;
use App\Infrastructure\Locator\InMemoryAddressBatchJobRepository;
use App\Infrastructure\Locator\InMemoryAddressBatchMessageBus;
use App\Infrastructure\Locator\InMemoryAddressBatchResultStorage;
use App\Message\Locator\AddressBatchMessage;
use App\MessageHandler\Locator\AddressBatchMessageHandler;
use App\Service\Locator\AddressBatchService;
use App\ServiceInterface\Locator\AddressPipelineInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchServiceTest extends TestCase
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

        $service = new AddressBatchService($jobRepository, $messageBus, $resultStorage);

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
