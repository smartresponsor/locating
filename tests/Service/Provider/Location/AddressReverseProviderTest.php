<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\AddressReverseGateway;
use App\Locating\Infrastructure\Provider\Location\LocationMetricRecorder;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\LocationMetricBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Http\AddressReverseHttpBackendInterface;
use App\Locating\Service\Address\Location\LocationResultFactory;
use App\Locating\Service\Provider\Location\AddressReverseProvider;
use PHPUnit\Framework\TestCase;

final class AddressReverseProviderTest extends TestCase
{
    public function testReverseMapsPayloadAndRecordsMetrics(): void
    {
        $client = new class () implements AddressReverseHttpBackendInterface {
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

        $metricRecorder = new class () implements LocationMetricBackendInterface {
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

        $provider = new AddressReverseProvider(new AddressReverseGateway($client), new LocationResultFactory(), new LocationMetricRecorder($metricRecorder));
        $result = $provider->reverse(29.7604, -95.3698, 'US');

        self::assertSame('verified', $result->status());
        self::assertSame('Main St 10', $result->address()?->toArray()['street']);
        self::assertSame('US', $result->address()?->toArray()['countryCode']);
        self::assertSame('nominatim', $result->providerKey());
        self::assertCount(1, $metricRecorder->latencies);
        self::assertSame(['address_reverse', 'ok'], $metricRecorder->counters[0]);
    }
}
