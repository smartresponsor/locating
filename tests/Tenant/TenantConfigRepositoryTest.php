<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Tests\Tenant;

use App\Infrastructure\ArrayTenantConfigRepository;
use PHPUnit\Framework\TestCase;

final class TenantConfigRepositoryTest extends TestCase
{
    public function testRepositoryFindsConfiguredLimit(): void
    {
        $config = [
            [
                'tenantId' => 'tenant-one',
                'operation' => 'address_check',
                'limitPerMinute' => 10,
            ],
            [
                'tenantId' => 'tenant-two',
                'operation' => 'address_check',
                'limitPerMinute' => 5,
            ],
        ];

        $repository = new ArrayTenantConfigRepository($config);

        $limit = $repository->findLimit('tenant-one', 'address_check');
        $this->assertNotNull($limit);
        $this->assertSame('tenant-one', $limit->tenantId());
        $this->assertSame('address_check', $limit->operation());
        $this->assertSame(10, $limit->limitPerMinute());

        $limitTwo = $repository->findLimit('tenant-two', 'address_check');
        $this->assertNotNull($limitTwo);
        $this->assertSame('tenant-two', $limitTwo->tenantId());
        $this->assertSame('address_check', $limitTwo->operation());
        $this->assertSame(5, $limitTwo->limitPerMinute());
    }

    public function testRepositoryReturnsNullForMissingLimit(): void
    {
        $config = [
            [
                'tenantId' => 'tenant-one',
                'operation' => 'address_check',
                'limitPerMinute' => 10,
            ],
        ];

        $repository = new ArrayTenantConfigRepository($config);

        $this->assertNull($repository->findLimit('tenant-two', 'address_check'));
        $this->assertNull($repository->findLimit('tenant-one', 'reverse_geocode'));
    }
}
