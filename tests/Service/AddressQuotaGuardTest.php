<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Tests\Service;

use Smartresponsor\EntityInterface\TenantContextInterface;
use Smartresponsor\EntityInterface\TenantLimitInterface;
use Smartresponsor\Service\AddressQuotaGuard;
use PHPUnit\Framework\TestCase;

final class AddressQuotaGuardTest extends TestCase
{
    public function testAllowedWhenNoLimitConfigured(): void
    {
        $tenantContext = new class implements TenantContextInterface {
            public function id(): string
            {
                return 'tenant-demo';
            }
        };

        $configRepository = new class implements \Smartresponsor\InfrastructureInterface\TenantConfigRepositoryInterface {
            public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface
            {
                return null;
            }
        };

        $usageCounter = new class implements \Smartresponsor\InfrastructureInterface\TenantUsageCounterInterface {
            public function increment(string $tenantId, string $operation, int $limitPerMinute): bool
            {
                return true;
            }
        };

        $guard = new AddressQuotaGuard($tenantContext, $configRepository, $usageCounter);

        self::assertTrue($guard->isAllowed('address_check'));
    }

    public function testDeniedWhenLimitIsZero(): void
    {
        $tenantContext = new class implements TenantContextInterface {
            public function id(): string
            {
                return 'tenant-zero';
            }
        };

        $limit = new class implements TenantLimitInterface {
            public function tenantId(): string
            {
                return 'tenant-zero';
            }

            public function operation(): string
            {
                return 'address_check';
            }

            public function limitPerMinute(): int
            {
                return 0;
            }
        };

        $configRepository = new class($limit) implements \Smartresponsor\InfrastructureInterface\TenantConfigRepositoryInterface {
            public function __construct(private TenantLimitInterface $limit)
            {
            }

            public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface
            {
                return $this->limit;
            }
        };

        $usageCounter = new class implements \Smartresponsor\InfrastructureInterface\TenantUsageCounterInterface {
            public function increment(string $tenantId, string $operation, int $limitPerMinute): bool
            {
                throw new \RuntimeException('increment must not be called when limitPerMinute is zero');
            }
        };

        $guard = new AddressQuotaGuard($tenantContext, $configRepository, $usageCounter);

        self::assertFalse($guard->isAllowed('address_check'));
    }

    public function testDelegatesToUsageCounterWhenLimitPositive(): void
    {
        $tenantContext = new class implements TenantContextInterface {
            public function id(): string
            {
                return 'tenant-one';
            }
        };

        $limit = new class implements TenantLimitInterface {
            public function tenantId(): string
            {
                return 'tenant-one';
            }

            public function operation(): string
            {
                return 'address_check';
            }

            public function limitPerMinute(): int
            {
                return 10;
            }
        };

        $configRepository = new class($limit) implements \Smartresponsor\InfrastructureInterface\TenantConfigRepositoryInterface {
            public function __construct(private TenantLimitInterface $limit)
            {
            }

            public function findLimit(string $tenantId, string $operation): ?TenantLimitInterface
            {
                return $this->limit;
            }
        };

        $usageCounter = new class implements \Smartresponsor\InfrastructureInterface\TenantUsageCounterInterface {
            public int $callCount = 0;
            public array $lastArgs = [];

            public function increment(string $tenantId, string $operation, int $limitPerMinute): bool
            {
                $this->callCount++;
                $this->lastArgs = [$tenantId, $operation, $limitPerMinute];

                return true;
            }
        };

        $guard = new AddressQuotaGuard($tenantContext, $configRepository, $usageCounter);

        self::assertTrue($guard->isAllowed('address_check'));
        self::assertSame(1, $usageCounter->callCount);
        self::assertSame(['tenant-one', 'address_check', 10], $usageCounter->lastArgs);
    }
}
