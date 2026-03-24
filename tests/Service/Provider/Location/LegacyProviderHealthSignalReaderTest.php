<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotStoreInterface;
use App\Service\Provider\Location\LegacyProviderHealthSignalReader;
use PHPUnit\Framework\TestCase;

final class LegacyProviderHealthSignalReaderTest extends TestCase
{
    public function testItBuildsAppOwnedSignalFromSnapshotStore(): void
    {
        $reader = new LegacyProviderHealthSignalReader(new class implements ProviderHealthSnapshotStoreInterface {
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
