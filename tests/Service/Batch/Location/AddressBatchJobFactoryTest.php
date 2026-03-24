<?php

declare(strict_types=1);

namespace App\Tests\Service\Batch\Location;

use App\Entity\Location\AddressBatchJobStatus;
use App\Service\Batch\Location\AddressBatchJobFactory;
use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\Locator\AddressBatchJob;

final class AddressBatchJobFactoryTest extends TestCase
{
    public function testCreatesAppBatchJobFromLegacyBatchJob(): void
    {
        $legacyJob = new AddressBatchJob('job-1', 'tenant-demo', 4);
        $legacyJob->markRun();
        $legacyJob->incrementProcessed();

        $factory = new AddressBatchJobFactory();
        $job = $factory->create($legacyJob);

        self::assertSame('job-1', $job->jobId());
        self::assertSame('tenant-demo', $job->tenantId());
        self::assertSame(AddressBatchJobStatus::RUNNING, $job->jobStatus());
        self::assertSame(4, $job->totalCount());
        self::assertSame(1, $job->processedCount());
    }
}
