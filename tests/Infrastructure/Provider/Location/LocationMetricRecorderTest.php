<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Recorder\LocationMetricRecorder;
use App\Locating\ServiceInterface\Provider\Location\LocationMetricBackendInterface;
use PHPUnit\Framework\TestCase;

final class LocationMetricRecorderTest extends TestCase
{
    public function testRecorderDelegatesToMetricBackend(): void
    {
        $backend = new class () implements LocationMetricBackendInterface {
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

        $recorder = new LocationMetricRecorder($backend);
        $recorder->recordLatency('x', 12.5);
        $recorder->incrementCounter('x', 'ok');

        self::assertSame([['x', 12.5]], $backend->latencies);
        self::assertSame([['x', 'ok']], $backend->counters);
    }
}
