<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\LocationMetricLegacyRecorderInterface;
use App\Infrastructure\Provider\Location\LegacyLocationMetricRecorder;
use PHPUnit\Framework\TestCase;

final class LegacyLocationMetricRecorderTest extends TestCase
{
    public function testRecorderDelegatesToLegacyMetricRecorder(): void
    {
        $legacy = new class implements LocationMetricLegacyRecorderInterface {
            public array $latencies = [];
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

        $recorder = new LegacyLocationMetricRecorder($legacy);
        $recorder->recordLatency('x', 12.5);
        $recorder->incrementCounter('x', 'ok');

        self::assertSame([['x', 12.5]], $legacy->latencies);
        self::assertSame([['x', 'ok']], $legacy->counters);
    }
}
