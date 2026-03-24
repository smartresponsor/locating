<?php

declare(strict_types=1);

namespace App\Tests\Service\Batch\Location;

use App\Entity\Location\AddressPipelineResult;
use App\Entity\Location\AddressView;
use App\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus;
use App\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore;
use App\Infrastructure\Batch\Location\MessageBusAddressBatchMessageDispatcher;
use App\MessageHandler\Batch\Location\AddressBatchMessageHandler;
use App\Service\Batch\Location\AddressBatchService;
use App\ServiceInterface\Address\Location\AddressPipelineInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchServiceTest extends TestCase
{
    public function testCreateJobAndProcessItemsWithAppOwnedInMemoryRuntime(): void
    {
        $runtimeStore = new InMemoryAddressBatchRuntimeStore();

        $pipeline = new class implements AddressPipelineInterface {
            public function process(\App\EntityInterface\Location\AddressInputInterface $input): \App\EntityInterface\Location\AddressPipelineResultInterface
            {
                return new AddressPipelineResult(
                    AddressPipelineResult::STATUS_VERIFIED,
                    new AddressView('Foo Street', 'Houston', 'TX', '77001', 'US'),
                    [],
                );
            }
        };

        $handler = new AddressBatchMessageHandler($pipeline, $runtimeStore, $runtimeStore);
        $messageBus = new InMemoryAddressBatchMessageBus($handler);

        $service = new AddressBatchService(
            $runtimeStore,
            new MessageBusAddressBatchMessageDispatcher($messageBus),
            $runtimeStore,
        );

        $job = $service->createJob('tenant-demo', [
            ['raw' => 'foo street', 'data' => ['country' => 'US']],
            ['raw' => 'bar avenue', 'data' => ['country' => 'US']],
        ]);

        self::assertSame(2, $job->totalCount());
        self::assertSame(2, $job->processedCount());
        self::assertSame('completed', $job->jobStatus()->value);

        $results = $service->jobResultList($job->jobId());
        self::assertCount(2, $results);
        self::assertSame('verified', $results[0]['status']);
        self::assertSame('Houston', $results[0]['address']['city']);
    }
}
