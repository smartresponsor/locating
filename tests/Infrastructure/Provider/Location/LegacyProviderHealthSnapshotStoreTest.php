<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderHealthLegacyMonitorInterface;
use App\Infrastructure\Provider\Location\LegacyProviderHealthSnapshotStore;
use PHPUnit\Framework\TestCase;

final class LegacyProviderHealthSnapshotStoreTest extends TestCase
{
    public function testItReturnsLegacySnapshotWithoutReshaping(): void
    {
        $store = new LegacyProviderHealthSnapshotStore(new class implements ProviderHealthLegacyMonitorInterface {
            public function update(string $providerKey, bool $success, float $durationMs): void
            {
            }

            public function snapshot(): array
            {
                return [
                    'primary' => [
                        'successRate' => 0.81,
                        'ewmaMs' => 220.0,
                    ],
                ];
            }
        });

        self::assertSame([
            'primary' => [
                'successRate' => 0.81,
                'ewmaMs' => 220.0,
            ],
        ], $store->snapshot());
    }
}
