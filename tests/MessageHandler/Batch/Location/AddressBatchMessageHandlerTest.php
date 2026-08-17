<?php

declare(strict_types=1);

namespace App\Locating\Tests\MessageHandler\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore;
use App\Locating\Message\Batch\Location\AddressBatchMessage;
use App\Locating\MessageHandler\Batch\Location\AddressBatchMessageHandler;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\ServiceInterface\Address\Location\AddressPipelineInterface;
use PHPUnit\Framework\TestCase;

final class AddressBatchMessageHandlerTest extends TestCase
{
    public function testProcessesBatchMessageThroughAppOwnedRuntimeStore(): void
    {
        $runtimeStore = new InMemoryAddressBatchRuntimeStore();
        $job = $runtimeStore->create('tenant-a', 1);

        $pipeline = new class () implements AddressPipelineInterface {
            public function process(\App\Locating\ModelInterface\Location\AddressInputInterface $input): \App\Locating\ModelInterface\Location\AddressPipelineResultInterface
            {
                return new AddressPipelineResult(
                    AddressPipelineResult::STATUS_VERIFIED,
                    new AddressView('123 Main St', 'Houston', 'TX', '77001', 'US'),
                    [],
                );
            }
        };

        $handler = new AddressBatchMessageHandler($pipeline, $runtimeStore, $runtimeStore);
        $handler(new AddressBatchMessage($job->jobId(), ['raw' => '123 Main St', 'data' => ['country' => 'US']]));

        $reloaded = $runtimeStore->find($job->jobId());
        self::assertNotNull($reloaded);
        self::assertSame(1, $reloaded->processedCount());
        self::assertSame('completed', $reloaded->jobStatus()->value);

        $results = $runtimeStore->resultList($job->jobId());
        /** @var list<array{status:string,address:array{street:string}}> $results */
        self::assertCount(1, $results);
        self::assertSame('verified', $results[0]['status']);
        self::assertSame('123 Main St', $results[0]['address']['street']);
    }
}
