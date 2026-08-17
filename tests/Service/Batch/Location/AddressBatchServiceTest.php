<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\InMemoryAddressBatchMessageBus;
use App\Locating\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore;
use App\Locating\Infrastructure\Batch\Location\MessageBusAddressBatchMessageDispatcher;
use App\Locating\MessageHandler\Batch\Location\AddressBatchMessageHandler;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Batch\Location\LocationAddressBatchService;
use App\Locating\ServiceInterface\Address\Location\AddressPipelineInterface;
use PHPUnit\Framework\TestCase;

final class LocationAddressBatchServiceTest extends TestCase
{
    public function testCreateJobAndProcessItemsWithAppOwnedInMemoryRuntime(): void
    {
        $runtimeStore = new InMemoryAddressBatchRuntimeStore();

        $pipeline = new class () implements AddressPipelineInterface {
            public function process(\App\Locating\ModelInterface\Location\AddressInputInterface $input): \App\Locating\ModelInterface\Location\AddressPipelineResultInterface
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

        $service = new LocationAddressBatchService(
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
        /** @var list<array{status:string,address:array{city:string}}> $results */
        self::assertCount(2, $results);
        self::assertSame('verified', $results[0]['status']);
        self::assertSame('Houston', $results[0]['address']['city']);
    }
}
