<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\ReadModel\Observability\Location\ProviderGovernanceMetricSet;
use PHPUnit\Framework\TestCase;

final class ProviderGovernanceMetricSetTest extends TestCase
{
    public function testMetricSetRendersPrometheusBody(): void
    {
        $set = new ProviderGovernanceMetricSet('location', [
            '# HELP locator_provider_degraded Locator provider degraded state by source.',
            'locator_provider_degraded{source="legacy-suggest",operation="suggest"} 1',
        ]);

        self::assertSame('location', $set->service());
        self::assertCount(2, $set->lines());
        self::assertStringContainsString('locator_provider_degraded', $set->toPrometheus());
    }
}
