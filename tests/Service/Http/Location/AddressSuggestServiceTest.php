<?php

declare(strict_types=1);

namespace Tests\Service\Http\Location;

use App\Entity\Location\AddressSuggestionResult;
use App\Entity\Location\AddressSuggestionView;
use App\Entity\Location\AddressView;
use App\Service\Http\Location\AddressSuggestService;
use App\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;
use App\ServiceInterface\Http\Location\LocationViewFactoryInterface;
use PHPUnit\Framework\TestCase;

final class AddressSuggestServiceTest extends TestCase
{
    public function testUsesAppOwnedCapabilityResultInsteadOfLegacyLocatorEntity(): void
    {
        $capability = new class implements AddressSuggestCapabilityInterface {
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

        $factory = new class implements LocationViewFactoryInterface {
            public function createSuggestionView(\App\EntityInterface\Location\AddressSuggestionResultInterface $suggestion): \App\EntityInterface\Location\AddressSuggestionViewInterface
            {
                return new AddressSuggestionView(
                    $suggestion->label(),
                    $suggestion->address(),
                    $suggestion->providerKey(),
                );
            }

            public function createReverseView(\App\EntityInterface\Location\AddressReverseResultInterface $result): \App\EntityInterface\Location\AddressReverseViewInterface
            {
                throw new \LogicException('Not needed.');
            }
        };

        $service = new AddressSuggestService($capability, $factory);
        $items = $service->suggest('Main', 'US');

        self::assertCount(1, $items);
        self::assertSame('Main St, Houston, TX', $items[0]->label());
    }
}
