<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderHealthLegacyMonitorInterface;
use App\Infrastructure\Provider\Location\SmartresponsorProviderHealthSnapshotBackend;
use PHPUnit\Framework\TestCase;

final class SmartresponsorProviderHealthSnapshotBackendTest extends TestCase
{
    public function testSnapshotDelegatesToLegacyMonitor(): void
    {
        $backend = new SmartresponsorProviderHealthSnapshotBackend(new class implements ProviderHealthLegacyMonitorInterface {
            public function update(string $provider, bool $ok, float $ms): void
            {
            }

            public function snapshot(): array
            {
                return ['p' => ['successRate' => 0.9]];
            }
        });
        self::assertSame(['p' => ['successRate' => 0.9]], $backend->snapshot());
    }
}
