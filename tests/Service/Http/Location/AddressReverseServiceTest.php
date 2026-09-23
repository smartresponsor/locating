<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http\Location;

use App\Locating\FactoryInterface\Http\Location\LocationViewFactoryInterface;
use App\Locating\Model\Location\AddressReverseResult;
use App\Locating\Model\Location\AddressReverseView;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Http\Location\LocationAddressReverseService;
use App\Locating\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use PHPUnit\Framework\TestCase;

final class LocationAddressReverseServiceTest extends TestCase
{
    public function testUsesAppOwnedCapabilityResultInsteadOfLocatorEntity(): void
    {
        $capability = new class () implements AddressReverseCapabilityInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\Locating\ModelInterface\Location\AddressReverseResultInterface
            {
                TestCase::assertSame(29.7604, $latitude);
                TestCase::assertSame(-95.3698, $longitude);
                TestCase::assertSame('US', $countryCode);

                return new AddressReverseResult(
                    'valid',
                    new AddressView('Main St', 'Houston', 'TX', '77002', 'US'),
                    [],
                    ['latitude' => $latitude, 'longitude' => $longitude],
                    'here',
                );
            }
        };

        $factory = new class () implements LocationViewFactoryInterface {
            public function createSuggestionView(\App\Locating\ModelInterface\Location\AddressSuggestionResultInterface $suggestion): \App\Locating\ModelInterface\Location\AddressSuggestionViewInterface
            {
                throw new \LogicException('Not needed.');
            }

            public function createReverseView(\App\Locating\ModelInterface\Location\AddressReverseResultInterface $result): \App\Locating\ModelInterface\Location\AddressReverseViewInterface
            {
                return new AddressReverseView(
                    $result->status(),
                    $result->address(),
                    $result->issues(),
                    $result->geoPoint(),
                    $result->providerKey(),
                );
            }
        };

        $service = new LocationAddressReverseService($capability, $factory);
        $item = $service->reverse(29.7604, -95.3698, 'US');

        self::assertSame('valid', $item->status());
        self::assertSame('here', $item->providerKey());
    }
}
