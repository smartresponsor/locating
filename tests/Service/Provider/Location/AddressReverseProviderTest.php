<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Contract\Location\AddressReverseHttpBackendInterface;
use App\Locating\Factory\Address\Location\LocationResultFactory;
use App\Locating\Recorder\LocationMetricRecorder;
use App\Locating\Service\Provider\Location\AddressReverseGateway;
use App\Locating\Service\Provider\Location\AddressReverseProvider;
use App\Locating\ServiceInterface\Provider\Location\LocationMetricBackendInterface;
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
            /** @var list<array{string, float}> */
            public array $latencies = [];
            /** @var list<array{string, string}> */
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
        $address = $result->address();
        self::assertNotNull($address);
        self::assertSame('Main St 10', $address->toArray()['street']);
        self::assertSame('US', $address->toArray()['countryCode']);
        self::assertSame('nominatim', $result->providerKey());
        self::assertCount(1, $metricRecorder->latencies);
        self::assertSame(['address_reverse', 'ok'], $metricRecorder->counters[0]);
    }
}
