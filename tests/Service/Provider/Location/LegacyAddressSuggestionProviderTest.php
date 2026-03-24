<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Infrastructure\Provider\Location\LegacyAddressSuggestGateway;
use App\Service\Address\Location\LocationResultFactory;
use App\Service\Provider\Location\LegacyAddressSuggestionProvider;
use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressSuggestion;

final class LegacyAddressSuggestionProviderTest extends TestCase
{
    public function testSuggestAggregatesAcrossLegacyProviders(): void
    {
        $providers = [
            new class implements AddressSuggestProviderInterface {
                public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
                {
                    return [
                        new AddressSuggestion('One', AddressData::fromArray(['street' => 'A', 'city' => 'X', 'region' => 'TX', 'postalCode' => '1', 'countryCode' => 'US']), 'p1'),
                        new AddressSuggestion('Two', AddressData::fromArray(['street' => 'B', 'city' => 'Y', 'region' => 'TX', 'postalCode' => '2', 'countryCode' => 'US']), 'p1'),
                    ];
                }
            },
            new class implements AddressSuggestProviderInterface {
                public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
                {
                    return [
                        new AddressSuggestion('Three', AddressData::fromArray(['street' => 'C', 'city' => 'Z', 'region' => 'TX', 'postalCode' => '3', 'countryCode' => 'US']), 'p2'),
                    ];
                }
            },
        ];

        $provider = new LegacyAddressSuggestionProvider(new LegacyAddressSuggestGateway($providers), new LocationResultFactory());
        $items = $provider->suggest('Main', 'US', 2);

        self::assertCount(2, $items);
        self::assertSame('One', $items[0]->label());
        self::assertSame('Two', $items[1]->label());
    }

    public function testSuggestReturnsEmptyArrayForInvalidInput(): void
    {
        $provider = new LegacyAddressSuggestionProvider(new LegacyAddressSuggestGateway([]), new LocationResultFactory());

        self::assertSame([], $provider->suggest('', 'US', 5));
        self::assertSame([], $provider->suggest('Main', 'US', 0));
    }
}
