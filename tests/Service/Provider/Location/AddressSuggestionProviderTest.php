<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Service\Address\Location\LocationResultFactory;
use App\Locating\Service\Provider\Location\AddressSuggestGateway;
use App\Locating\Service\Provider\Location\AddressSuggestionProvider;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestBackendInterface;
use PHPUnit\Framework\TestCase;

final class AddressSuggestionProviderTest extends TestCase
{
    public function testSuggestAggregatesAcrossProviders(): void
    {
        $backend = new class () implements AddressSuggestBackendInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [
                    ['label' => 'One', 'address' => ['street' => 'A', 'city' => 'X', 'region' => 'TX', 'postalCode' => '1', 'countryCode' => 'US'], 'providerKey' => 'p1'],
                    ['label' => 'Two', 'address' => ['street' => 'B', 'city' => 'Y', 'region' => 'TX', 'postalCode' => '2', 'countryCode' => 'US'], 'providerKey' => 'p1'],
                    ['label' => 'Three', 'address' => ['street' => 'C', 'city' => 'Z', 'region' => 'TX', 'postalCode' => '3', 'countryCode' => 'US'], 'providerKey' => 'p2'],
                ];
            }
        };

        $provider = new AddressSuggestionProvider(new AddressSuggestGateway($backend), new LocationResultFactory());
        $items = $provider->suggest('Main', 'US', 2);

        self::assertCount(2, $items);
        self::assertSame('One', $items[0]->label());
        self::assertSame('Two', $items[1]->label());
    }

    public function testSuggestReturnsEmptyArrayForInvalidInput(): void
    {
        $provider = new AddressSuggestionProvider(new AddressSuggestGateway(new class () implements AddressSuggestBackendInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [];
            }
        }), new LocationResultFactory());

        self::assertSame([], $provider->suggest('', 'US', 5));
        self::assertSame([], $provider->suggest('Main', 'US', 0));
    }
}
