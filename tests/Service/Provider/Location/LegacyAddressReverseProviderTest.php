<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Infrastructure\Provider\Location\LegacyAddressReverseGateway;
use App\Infrastructure\Provider\Location\LegacyLocationMetricRecorder;
use App\Service\Address\Location\LocationResultFactory;
use App\Service\Provider\Location\LegacyAddressReverseProvider;
use PHPUnit\Framework\TestCase;
use Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface;

final class LegacyAddressReverseProviderTest extends TestCase
{
    public function testReverseMapsPayloadAndRecordsMetrics(): void
    {
        $client = new class implements ReverseHttpClientInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
            {
                return [
                    'address' => [
                        'road' => 'Main St',
                        'house_number' => '10',
                        'city' => 'Houston',
                        'state' => 'Texas',
                        'postcode' => '77001',
                        'country_code' => 'us',
                    ],
                ];
            }
        };

        $metricRecorder = new class implements MetricRecorderInterface {
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

        $provider = new LegacyAddressReverseProvider(new LegacyAddressReverseGateway($client), new LocationResultFactory(), new LegacyLocationMetricRecorder($metricRecorder));
        $result = $provider->reverse(29.7604, -95.3698, 'US');

        self::assertSame('verified', $result->status());
        self::assertSame('Main St 10', $result->address()?->toArray()['street']);
        self::assertSame('US', $result->address()?->toArray()['countryCode']);
        self::assertSame('nominatim', $result->providerKey());
        self::assertCount(1, $metricRecorder->latencies);
        self::assertSame(['address_reverse', 'ok'], $metricRecorder->counters[0]);
    }
}
