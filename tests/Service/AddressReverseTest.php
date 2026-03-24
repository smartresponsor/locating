<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\AddressStatus;
use App\Infrastructure\InMemoryMetricRecorder;
use App\Service\AddressReverse;
use PHPUnit\Framework\TestCase;

final class AddressReverseTest extends TestCase
{
    public function testReverseBuildsVerifiedResultWhenAddressIsComplete(): void
    {
        $client = new class() implements \App\InfrastructureInterface\ReverseHttpClientInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
            {
                return [
                    'address' => [
                        'road' => '1600 Pennsylvania Ave NW',
                        'house_number' => '',
                        'city' => 'Washington',
                        'state' => 'DC',
                        'postcode' => '20500',
                        'country' => 'United States',
                        'country_code' => 'us',
                    ],
                ];
            }
        };

        $metrics = new InMemoryMetricRecorder();
        $service = new AddressReverse($client, $metrics);

        $result = $service->reverse(38.8977, -77.0365, 'US');

        self::assertSame(AddressStatus::VERIFIED, $result->status());
        $data = $result->addressData()->toArray();

        self::assertSame('1600 Pennsylvania Ave NW', $data['street']);
        self::assertSame('Washington', $data['city']);
        self::assertSame('DC', $data['region']);
        self::assertSame('20500', $data['postalCode']);
        self::assertSame('US', $data['countryCode']);
    }

    public function testReverseMarksRejectedWhenAlmostNoData(): void
    {
        $client = new class() implements \App\InfrastructureInterface\ReverseHttpClientInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
            {
                return [
                    'address' => [
                        'country_code' => 'us',
                    ],
                ];
            }
        };

        $service = new AddressReverse($client, null);

        $result = $service->reverse(0.0, 0.0, 'US');

        self::assertSame(AddressStatus::REJECTED, $result->status());
    }
}
