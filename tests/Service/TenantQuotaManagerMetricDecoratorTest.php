<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Tests\Service;

use Smartresponsor\Infrastructure\InMemoryMetricRecorder;
use Smartresponsor\Service\TenantQuotaManager;
use Smartresponsor\Service\TenantQuotaManagerMetricDecorator;
use PHPUnit\Framework\TestCase;

final class TenantQuotaManagerMetricDecoratorTest extends TestCase
{
    public function testQuotaMetricOkAndErrorRecorded(): void
    {
        $inner = new TenantQuotaManager([
            'default' => [
                'geocode' => ['limit' => 1, 'used' => 0],
            ],
        ]);
        $metricRecorder = new InMemoryMetricRecorder();
        $decorator = new TenantQuotaManagerMetricDecorator($inner, $metricRecorder);

        self::assertTrue($decorator->allow('default', 'geocode', 1, true));
        self::assertFalse($decorator->allow('default', 'geocode', 1, true));

        $snapshot = $metricRecorder->snapshot();

        self::assertArrayHasKey('tenant_quota_geocode', $snapshot);
        $metric = $snapshot['tenant_quota_geocode'];

        self::assertSame(2, $metric['count']);
        self::assertSame(1, $metric['errorCount']);
    }
}
