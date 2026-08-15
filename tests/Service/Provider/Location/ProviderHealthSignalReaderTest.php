<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderHealthSnapshotStoreInterface;
use App\Locating\Service\Provider\Location\ProviderHealthSignalReader;
use PHPUnit\Framework\TestCase;

final class ProviderHealthSignalReaderTest extends TestCase
{
    public function testItBuildsAppOwnedSignalFromSnapshotStore(): void
    {
        $reader = new ProviderHealthSignalReader(new class () implements ProviderHealthSnapshotStoreInterface {
            public function snapshot(): array
            {
                return [
                    'primary' => [
                        'successRate' => 0.92,
                        'ewmaMs' => 180.0,
                    ],
                ];
            }
        });

        $signal = $reader->read('primary');

        self::assertSame('primary', $signal->sourceKey());
        self::assertSame(0.92, $signal->successRate());
        self::assertSame(180.0, $signal->ewmaMs());
    }
}
