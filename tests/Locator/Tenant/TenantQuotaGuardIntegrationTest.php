<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Tests\Locator\Tenant;

use App\Entity\Locator\TenantContext;
use App\Infrastructure\Locator\ArrayTenantConfigRepository;
use App\Infrastructure\Locator\InMemoryTenantUsageCounter;
use App\Service\Locator\AddressQuotaGuard;
use PHPUnit\Framework\TestCase;

final class TenantQuotaGuardIntegrationTest extends TestCase
{
    public function testGuardAllowsWhenLimitMissing(): void
    {
        $tenantContext = new TenantContext('tenant-one');
        $configRepository = new ArrayTenantConfigRepository([]);
        $usageCounter = new InMemoryTenantUsageCounter();

        $guard = new AddressQuotaGuard($tenantContext, $configRepository, $usageCounter);

        $this->assertTrue($guard->isAllowed('address_check'));
    }

    public function testGuardEnforcesLimitPerTenantOperation(): void
    {
        $tenantContext = new TenantContext('tenant-one');
        $configRepository = new ArrayTenantConfigRepository([
            [
                'tenantId' => 'tenant-one',
                'operation' => 'address_check',
                'limitPerMinute' => 2,
            ],
        ]);
        $usageCounter = new InMemoryTenantUsageCounter();

        $guard = new AddressQuotaGuard($tenantContext, $configRepository, $usageCounter);

        $this->assertTrue($guard->isAllowed('address_check'));
        $this->assertTrue($guard->isAllowed('address_check'));
        $this->assertFalse($guard->isAllowed('address_check'));
    }
}
