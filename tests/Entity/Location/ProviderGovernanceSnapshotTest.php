<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderGovernanceSnapshot;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceSnapshotTest extends TestCase
{
    public function testItExposesGovernanceData(): void
    {
        $snapshot = new ProviderGovernanceSnapshot('legacy-suggest', 'suggest', 0.97, 123.0, true, 0.42);

        self::assertSame('legacy-suggest', $snapshot->sourceKey());
        self::assertSame('suggest', $snapshot->operation());
        self::assertSame(0.97, $snapshot->successRate());
        self::assertSame(123.0, $snapshot->ewmaMs());
        self::assertTrue($snapshot->quotaAllowed());
        self::assertSame(0.42, $snapshot->unitCost());
    }
}
