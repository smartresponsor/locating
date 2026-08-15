<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Address\Location;

use App\Locating\Model\Location\AddressSuggestionResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Address\Location\AddressSuggestCapability;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface;
use PHPUnit\Framework\TestCase;

final class AddressSuggestCapabilityTest extends TestCase
{
    public function testSuggestDelegatesToAppOwnedProviderBoundary(): void
    {
        $provider = new class () implements AddressSuggestionProviderInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [
                    new AddressSuggestionResult('One', new AddressView('A', 'Houston', 'TX', '77001', 'US'), 'p1'),
                    new AddressSuggestionResult('Two', new AddressView('B', 'Houston', 'TX', '77002', 'US'), 'p1'),
                ];
            }
        };

        $service = new AddressSuggestCapability($provider);
        $items = $service->suggest('Main', 'US', 2);

        self::assertCount(2, $items);
        self::assertSame('One', $items[0]->label());
        self::assertSame('Two', $items[1]->label());
    }
}
