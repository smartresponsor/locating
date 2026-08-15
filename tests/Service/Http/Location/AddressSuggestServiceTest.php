<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http\Location;

use App\Locating\Model\Location\AddressSuggestionResult;
use App\Locating\Model\Location\AddressSuggestionView;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Http\Location\LocationAddressSuggestService;
use App\Locating\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;
use App\Locating\ServiceInterface\Http\Location\LocationViewFactoryInterface;
use PHPUnit\Framework\TestCase;

final class LocationAddressSuggestServiceTest extends TestCase
{
    public function testUsesAppOwnedCapabilityResultInsteadOfLocatorEntity(): void
    {
        $capability = new class () implements AddressSuggestCapabilityInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                TestCase::assertSame('Main', $query);
                TestCase::assertSame('US', $countryCode);
                TestCase::assertSame(5, $limit);

                return [new AddressSuggestionResult(
                    'Main St, Houston, TX',
                    new AddressView('Main St', 'Houston', 'TX', '77002', 'US'),
                    'mapbox',
                )];
            }
        };

        $factory = new class () implements LocationViewFactoryInterface {
            public function createSuggestionView(\App\Locating\ModelInterface\Location\AddressSuggestionResultInterface $suggestion): \App\Locating\ModelInterface\Location\AddressSuggestionViewInterface
            {
                return new AddressSuggestionView(
                    $suggestion->label(),
                    $suggestion->address(),
                    $suggestion->providerKey(),
                );
            }

            public function createReverseView(\App\Locating\ModelInterface\Location\AddressReverseResultInterface $result): \App\Locating\ModelInterface\Location\AddressReverseViewInterface
            {
                throw new \LogicException('Not needed.');
            }
        };

        $service = new LocationAddressSuggestService($capability, $factory);
        $items = $service->suggest('Main', 'US');

        self::assertCount(1, $items);
        self::assertSame('Main St, Houston, TX', $items[0]->label());
    }
}
