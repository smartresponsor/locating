<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Tests\Entity\Location;

use App\Entity\Location\LocationStatusReport;
use App\Entity\Location\ProviderMetricSnapshot;
use PHPUnit\Framework\TestCase;

final class LocationStatusReportTest extends TestCase
{
    public function testToArrayExportsStatusPayload(): void
    {
        $report = new LocationStatusReport('location', 'ok', [
            'suggest' => new ProviderMetricSnapshot('suggest', 2, 0, 8.0, 0.0),
        ]);

        self::assertSame('location', $report->service());
        self::assertSame('ok', $report->status());
        self::assertArrayHasKey('suggest', $report->metrics());
        self::assertSame('location', $report->toArray()['service']);
        self::assertSame('ok', $report->toArray()['status']);
        self::assertSame(2, $report->toArray()['metrics']['suggest']['count']);
    }
}
