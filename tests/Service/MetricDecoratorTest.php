<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Tests\Service;

use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressResult;
use Smartresponsor\Entity\AddressStatus;
use Smartresponsor\InfrastructureInterface\MetricRecorderInterface;
use Smartresponsor\Service\AddressPipelineMetricDecorator;
use Smartresponsor\Service\AddressSuggestMetricDecorator;
use Smartresponsor\Service\LocationAddressBatchServiceMetricDecorator;
use Smartresponsor\ServiceInterface\AddressPipelineInterface;
use Smartresponsor\ServiceInterface\AddressSuggestInterface;
use Smartresponsor\ServiceInterface\LocationAddressBatchServiceInterface;

final class MetricDecoratorTest extends TestCase
{
    public function testAddressPipelineMetricDecoratorRecordsSuccess(): void
    {
        $inner = new class implements AddressPipelineInterface {
            public function process(AddressInput $input): AddressResult
            {
                return AddressResult::create(AddressStatus::VERIFIED, null);
            }
        };

        $recorder = new MetricRecorderSpy();

        $decorator = new AddressPipelineMetricDecorator($inner, $recorder);

        $result = $decorator->process(AddressInput::fromArray(['raw' => 'foo', 'data' => []]));

        self::assertInstanceOf(AddressResult::class, $result);
        self::assertArrayHasKey('address_pipeline', $recorder->latencyByOperation);
        self::assertSame(1, $recorder->counterByOperation['address_pipeline']['ok'] ?? 0);
    }

    public function testAddressSuggestMetricDecoratorRecordsError(): void
    {
        $inner = new class implements AddressSuggestInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                throw new \RuntimeException('fail');
            }
        };

        $recorder = new MetricRecorderSpy();
        $decorator = new AddressSuggestMetricDecorator($inner, $recorder);

        $this->expectException(\RuntimeException::class);
        try {
            $decorator->suggest('foo', 'US', 1);
        } finally {
            self::assertArrayHasKey('address_suggest', $recorder->latencyByOperation);
            self::assertSame(1, $recorder->counterByOperation['address_suggest']['error'] ?? 0);
        }
    }

    public function testLocationAddressBatchServiceMetricDecoratorRecordsCreateJob(): void
    {
        $inner = new class implements LocationAddressBatchServiceInterface {
            public function createJob(string $tenantId, array $itemList): \Smartresponsor\EntityInterface\AddressBatchJobInterface
            {
                return new class implements \Smartresponsor\EntityInterface\AddressBatchJobInterface {
                    public function jobId(): string
                    {
                        return 'job-1';
                    }

                    public function tenantId(): string
                    {
                        return 'tenant-demo';
                    }

                    public function jobStatus(): \Smartresponsor\Entity\AddressBatchJobStatus
                    {
                        return \Smartresponsor\Entity\AddressBatchJobStatus::PENDING;
                    }

                    public function totalCount(): int
                    {
                        return 0;
                    }

                    public function processedCount(): int
                    {
                        return 0;
                    }

                    public function createdAt(): \DateTimeImmutable
                    {
                        return new \DateTimeImmutable();
                    }

                    public function updatedAt(): \DateTimeImmutable
                    {
                        return new \DateTimeImmutable();
                    }
                };
            }

            public function jobStatus(string $jobId): ?\Smartresponsor\EntityInterface\AddressBatchJobInterface
            {
                return null;
            }

            public function jobResultList(string $jobId): array
            {
                return [];
            }
        };

        $recorder = new MetricRecorderSpy();
        $decorator = new LocationAddressBatchServiceMetricDecorator($inner, $recorder);

        $job = $decorator->createJob('tenant-demo', []);

        self::assertSame('job-1', $job->jobId());
        self::assertArrayHasKey('address_batch_create', $recorder->latencyByOperation);
        self::assertSame(1, $recorder->counterByOperation['address_batch_create']['ok'] ?? 0);
    }
}

final class MetricRecorderSpy implements MetricRecorderInterface
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
        if (!isset($this->counterByOperation[$operation])) {
            $this->counterByOperation[$operation] = [];
        }

        $this->counterByOperation[$operation][$result] = ($this->counterByOperation[$operation][$result] ?? 0) + 1;
    }
}
