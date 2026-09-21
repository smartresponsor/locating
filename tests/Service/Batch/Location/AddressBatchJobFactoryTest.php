<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Batch\Location;

use App\Locating\Model\Location\AddressBatchJobStatus;
use App\Locating\ModelInterface\Location\AddressBatchJobRecordInterface;
use App\Locating\Service\Batch\Location\AddressBatchJobFactory;
use PHPUnit\Framework\TestCase;

final class AddressBatchJobFactoryTest extends TestCase
{
    public function testCreatesAppBatchJobFromCanonicalBatchRecord(): void
    {
        $record = new class () implements AddressBatchJobRecordInterface {
            private string $status = 'running';

            public function jobId(): string
            {
                return 'job-1';
            }

            public function tenantId(): string
            {
                return 'tenant-demo';
            }

            public function jobStatusValue(): string
            {
                return $this->status;
            }

            public function totalCount(): int
            {
                return 4;
            }

            public function processedCount(): int
            {
                return 1;
            }

            public function createdAt(): \DateTimeImmutable
            {
                return new \DateTimeImmutable('2025-01-01 00:00:00');
            }

            public function updatedAt(): \DateTimeImmutable
            {
                return new \DateTimeImmutable('2025-01-01 00:01:00');
            }

            public function markRun(): void
            {
                $this->status = 'running';
            }

            public function incrementProcessed(): void
            {
                $this->status = 'running';
            }
        };

        $factory = new AddressBatchJobFactory();
        $job = $factory->create($record);

        self::assertSame('job-1', $job->jobId());
        self::assertSame('tenant-demo', $job->tenantId());
        self::assertSame(AddressBatchJobStatus::RUNNING, $job->jobStatus());
        self::assertSame(4, $job->totalCount());
        self::assertSame(1, $job->processedCount());
    }
}
