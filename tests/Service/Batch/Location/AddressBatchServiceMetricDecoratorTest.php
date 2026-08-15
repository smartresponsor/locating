<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Batch\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Metrics\LocationMetricRecorderInterface;
use App\Locating\Model\Location\AddressBatchJob;
use App\Locating\Model\Location\AddressBatchJobStatus;
use App\Locating\ModelInterface\Location\AddressBatchJobInterface;
use App\Locating\Service\Batch\Location\LocationAddressBatchServiceMetricDecorator;
use App\Locating\ServiceInterface\Batch\Location\LocationAddressBatchServiceInterface;
use PHPUnit\Framework\TestCase;

final class LocationAddressBatchServiceMetricDecoratorTest extends TestCase
{
    public function testCreateJobRecordsSuccessMetrics(): void
    {
        $inner = new class () implements LocationAddressBatchServiceInterface {
            public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface
            {
                return new AddressBatchJob('job-1', 'tenant-demo', AddressBatchJobStatus::PENDING, 0, 0, new \DateTimeImmutable(), new \DateTimeImmutable());
            }

            public function jobStatus(string $jobId): ?AddressBatchJobInterface
            {
                return null;
            }

            public function jobResultList(string $jobId): array
            {
                return [];
            }
        };

        $recorder = new BatchMetricRecorderSpy();
        $decorator = new LocationAddressBatchServiceMetricDecorator($inner, $recorder);

        $job = $decorator->createJob('tenant-demo', []);

        self::assertSame('job-1', $job->jobId());
        self::assertArrayHasKey('address_batch_create', $recorder->latencyByOperation);
        self::assertSame(1, $recorder->counterByOperation['address_batch_create']['ok'] ?? 0);
    }

    public function testCreateJobRecordsErrorMetrics(): void
    {
        $inner = new class () implements LocationAddressBatchServiceInterface {
            public function createJob(string $tenantId, array $itemList): AddressBatchJobInterface
            {
                throw new \RuntimeException('fail');
            }

            public function jobStatus(string $jobId): ?AddressBatchJobInterface
            {
                return null;
            }

            public function jobResultList(string $jobId): array
            {
                return [];
            }
        };

        $recorder = new BatchMetricRecorderSpy();
        $decorator = new LocationAddressBatchServiceMetricDecorator($inner, $recorder);

        $this->expectException(\RuntimeException::class);
        try {
            $decorator->createJob('tenant-demo', []);
        } finally {
            self::assertArrayHasKey('address_batch_create', $recorder->latencyByOperation);
            self::assertSame(1, $recorder->counterByOperation['address_batch_create']['error'] ?? 0);
        }
    }
}

final class BatchMetricRecorderSpy implements LocationMetricRecorderInterface
{
    /** @var array<string,float> */
    public array $latencyByOperation = [];

    /** @var array<string,array<string,int>> */
    public array $counterByOperation = [];

    public function recordLatency(string $operation, float $milliseconds): void
    {
        $this->latencyByOperation[$operation] = $milliseconds;
    }

    public function incrementCounter(string $operation, string $result): void
    {
        $this->counterByOperation[$operation][$result] = ($this->counterByOperation[$operation][$result] ?? 0) + 1;
    }
}
