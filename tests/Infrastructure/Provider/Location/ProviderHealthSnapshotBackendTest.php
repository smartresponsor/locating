<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\ProviderHealthSnapshotBackend;
use App\Locating\ServiceInterface\Provider\Location\Observability\ProviderHealthMonitorInterface;
use PHPUnit\Framework\TestCase;

final class ProviderHealthSnapshotBackendTest extends TestCase
{
    public function testSnapshotDelegatesToMonitor(): void
    {
        $backend = new ProviderHealthSnapshotBackend(new class () implements ProviderHealthMonitorInterface {
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
