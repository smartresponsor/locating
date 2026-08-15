<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderMetricSnapshot;
use PHPUnit\Framework\TestCase;

final class ProviderMetricSnapshotTest extends TestCase
{
    public function testSnapshotExposesTypedValues(): void
    {
        $snapshot = new ProviderMetricSnapshot('reverse', 10, 2, 18.5, 0.2);

        self::assertSame('reverse', $snapshot->operation());
        self::assertSame(10, $snapshot->count());
        self::assertSame(2, $snapshot->errorCount());
        self::assertSame(18.5, $snapshot->avgMs());
        self::assertSame(0.2, $snapshot->errorRate());
        self::assertSame([
            'count' => 10,
            'errorCount' => 2,
            'avgMs' => 18.5,
            'errorRate' => 0.2,
        ], $snapshot->toArray());
    }
}
