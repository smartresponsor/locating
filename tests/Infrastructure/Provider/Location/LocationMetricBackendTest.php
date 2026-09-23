<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\LocationMetricBackend;
use App\Locating\RecorderInterface\LocationMetricRecorderInterface;
use PHPUnit\Framework\TestCase;

final class LocationMetricBackendTest extends TestCase
{
    public function testRecorderDelegatesToRecorder(): void
    {
        $legacy = new class () implements LocationMetricRecorderInterface {
            /** @var list<array{string,float}> */
            public array $latencies = [];
            /** @var list<array{string,string}> */
            public array $counters = [];

            public function recordLatency(string $operation, float $milliseconds): void
            {
                $this->latencies[] = [$operation, $milliseconds];
            }

            public function incrementCounter(string $operation, string $result): void
            {
                $this->counters[] = [$operation, $result];
            }
        };
        $backend = new LocationMetricBackend($legacy);
        $backend->recordLatency('reverse', 12.0);
        $backend->incrementCounter('reverse', 'ok');
        self::assertSame([['reverse', 12.0]], $legacy->latencies);
        self::assertSame([['reverse', 'ok']], $legacy->counters);
    }
}
