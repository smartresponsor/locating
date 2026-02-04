<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Tests\Locator\Tenant;

use App\Infrastructure\Locator\InMemoryTenantUsageCounter;
use PHPUnit\Framework\TestCase;

final class TenantUsageCounterTest extends TestCase
{
    public function testLimitZeroIsDenied(): void
    {
        $counter = new InMemoryTenantUsageCounter();

        $this->assertFalse($counter->increment('tenant-one', 'address_check', 0));
        $this->assertFalse($counter->increment('tenant-one', 'address_check', -5));
    }

    public function testUsageStaysWithinLimit(): void
    {
        $counter = new InMemoryTenantUsageCounter();

        $this->assertTrue($counter->increment('tenant-one', 'address_check', 3));
        $this->assertTrue($counter->increment('tenant-one', 'address_check', 3));
        $this->assertTrue($counter->increment('tenant-one', 'address_check', 3));
        $this->assertFalse($counter->increment('tenant-one', 'address_check', 3));
        $this->assertFalse($counter->increment('tenant-one', 'address_check', 3));
    }
}
