<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\LocationMetricLegacyRecorderInterface;
use App\Infrastructure\Provider\Location\SmartresponsorLocationMetricBackend;
use PHPUnit\Framework\TestCase;

final class SmartresponsorLocationMetricBackendTest extends TestCase
{
    public function testRecorderDelegatesToLegacyRecorder(): void
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
        $backend = new SmartresponsorLocationMetricBackend($legacy);
        $backend->recordLatency('reverse', 12.0);
        $backend->incrementCounter('reverse', 'ok');
        self::assertSame([['reverse', 12.0]], $legacy->latencies);
        self::assertSame([['reverse', 'ok']], $legacy->counters);
    }
}
