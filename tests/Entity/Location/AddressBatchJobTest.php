<?php

declare(strict_types=1);

namespace App\Tests\Entity\Location;

use App\Entity\Location\AddressBatchJob;
use App\Entity\Location\AddressBatchJobStatus;
use PHPUnit\Framework\TestCase;

final class AddressBatchJobTest extends TestCase
{
    public function testExposesTypedBatchJobFields(): void
    {
        $createdAt = new \DateTimeImmutable('2026-03-21T12:00:00+00:00');
        $updatedAt = new \DateTimeImmutable('2026-03-21T12:05:00+00:00');
        $job = new AddressBatchJob('job-1', 'tenant-demo', AddressBatchJobStatus::RUNNING, 7, 3, $createdAt, $updatedAt);

        self::assertSame('job-1', $job->jobId());
        self::assertSame('tenant-demo', $job->tenantId());
        self::assertSame(AddressBatchJobStatus::RUNNING, $job->jobStatus());
        self::assertSame(7, $job->totalCount());
        self::assertSame(3, $job->processedCount());
        self::assertSame($createdAt, $job->createdAt());
        self::assertSame($updatedAt, $job->updatedAt());
    }
}
