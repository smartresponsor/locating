<?php

declare(strict_types=1);

namespace Tests\Service\Http\Location;

use App\Entity\Location\AddressReverseResult;
use App\Entity\Location\AddressReverseView;
use App\Entity\Location\AddressView;
use App\Service\Http\Location\AddressReverseService;
use App\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\ServiceInterface\Http\Location\LocationViewFactoryInterface;
use PHPUnit\Framework\TestCase;

final class AddressReverseServiceTest extends TestCase
{
    public function testUsesAppOwnedCapabilityResultInsteadOfLegacyLocatorEntity(): void
    {
        $capability = new class implements AddressReverseCapabilityInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\EntityInterface\Location\AddressReverseResultInterface
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

        $factory = new class implements LocationViewFactoryInterface {
            public function createSuggestionView(\App\EntityInterface\Location\AddressSuggestionResultInterface $suggestion): \App\EntityInterface\Location\AddressSuggestionViewInterface
            {
                throw new \LogicException('Not needed.');
            }

            public function createReverseView(\App\EntityInterface\Location\AddressReverseResultInterface $result): \App\EntityInterface\Location\AddressReverseViewInterface
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

        $service = new AddressReverseService($capability, $factory);
        $item = $service->reverse(29.7604, -95.3698, 'US');

        self::assertSame('valid', $item->status());
        self::assertSame('here', $item->providerKey());
    }
}
