<?php

declare(strict_types=1);

namespace Tests\Service\Address\Location;

use App\Entity\Location\AddressReverseResult;
use App\Entity\Location\AddressView;
use App\Service\Address\Location\AddressReverseCapability;
use App\ServiceInterface\Provider\Location\AddressReverseProviderInterface;
use PHPUnit\Framework\TestCase;

final class AddressReverseCapabilityTest extends TestCase
{
    public function testReverseDelegatesToAppOwnedProviderBoundary(): void
    {
        $provider = new class implements AddressReverseProviderInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\EntityInterface\Location\AddressReverseResultInterface
            {
                return new AddressReverseResult('verified', new AddressView('Main St 10', 'Houston', 'Texas', '77001', 'US'), [], ['latitude' => $latitude, 'longitude' => $longitude], 'nominatim');
            }
        };

        $service = new AddressReverseCapability($provider);
        $result = $service->reverse(29.7604, -95.3698, 'US');

        self::assertSame('verified', $result->status());
        self::assertSame('Main St 10', $result->address()?->toArray()['street']);
        self::assertSame('US', $result->address()?->toArray()['countryCode']);
        self::assertSame('nominatim', $result->providerKey());
    }
}
