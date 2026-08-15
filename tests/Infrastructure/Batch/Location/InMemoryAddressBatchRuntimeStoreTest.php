<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Batch\Location;

use App\Locating\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore;
use App\Locating\Model\Location\AddressBatchJobStatus;
use App\Locating\Model\Location\AddressIssue;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\Model\Location\AddressView;
use PHPUnit\Framework\TestCase;

final class InMemoryAddressBatchRuntimeStoreTest extends TestCase
{
    public function testCreatesTracksAndReadsBatchRuntimeState(): void
    {
        $store = new InMemoryAddressBatchRuntimeStore();
        $job = $store->create('tenant-a', 2);

        self::assertSame(AddressBatchJobStatus::PENDING, $job->jobStatus());
        self::assertTrue($store->markRun($job->jobId()));
        self::assertTrue($store->incrementProcessed($job->jobId()));

        $store->appendPipelineResult(
            $job->jobId(),
            new AddressPipelineResult(
                AddressPipelineResult::STATUS_PARTIAL,
                new AddressView('123 Main St', 'Houston', 'TX', '77001', 'US'),
                [new AddressIssue('postal-code', 'warning', 'Check postal code')],
            ),
        );

        $reloaded = $store->find($job->jobId());
        self::assertNotNull($reloaded);
        self::assertSame(1, $reloaded->processedCount());
        self::assertSame(AddressBatchJobStatus::RUNNING, $reloaded->jobStatus());

        $results = $store->resultList($job->jobId());
        self::assertCount(1, $results);
        self::assertSame('partial', $results[0]['status']);
        self::assertSame('Houston', $results[0]['address']['city']);
    }

    public function testMarksCompletedWhenProcessedCountReachesTotalCount(): void
    {
        $store = new InMemoryAddressBatchRuntimeStore();
        $job = $store->create('tenant-b', 1);

        self::assertTrue($store->markRun($job->jobId()));
        self::assertTrue($store->incrementProcessed($job->jobId()));

        $reloaded = $store->find($job->jobId());
        self::assertNotNull($reloaded);
        self::assertSame(AddressBatchJobStatus::COMPLETED, $reloaded->jobStatus());
    }
}
