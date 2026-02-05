<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Tests\Locator\Service;

use Smartresponsor\Infrastructure\Locator\InMemoryMetricRecorder;
use Smartresponsor\Service\Locator\TenantQuotaManager;
use Smartresponsor\Service\Locator\TenantQuotaManagerMetricDecorator;
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
