<?php

declare(strict_types=1);

namespace App\Tests\Infrastructure\Batch\Location;

use App\Entity\Location\AddressBatchJobStatus;
use App\Entity\Location\AddressIssue;
use App\Entity\Location\AddressPipelineResult;
use App\Entity\Location\AddressView;
use App\Infrastructure\Batch\Location\InMemoryAddressBatchRuntimeStore;
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
