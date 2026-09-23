<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderHealthSnapshotStore;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSnapshotBackendInterface;
use PHPUnit\Framework\TestCase;

final class ProviderHealthSnapshotStoreTest extends TestCase
{
    public function testItReturnsBackendSnapshotWithoutReshaping(): void
    {
        $store = new ProviderHealthSnapshotStore(new class () implements ProviderHealthSnapshotBackendInterface {
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
