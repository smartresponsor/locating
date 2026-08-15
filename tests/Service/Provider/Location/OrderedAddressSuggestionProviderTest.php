<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Model\Location\AddressSuggestionResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Provider\Location\AddressSuggestionRanker;
use App\Locating\Service\Provider\Location\OrderedAddressSuggestionProvider;
use App\Locating\Service\Provider\Location\StaticAddressSuggestionSourceOrder;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;
use PHPUnit\Framework\TestCase;

final class OrderedAddressSuggestionProviderTest extends TestCase
{
    public function testSuggestUsesAppOwnedOrderAndRankerSeams(): void
    {
        $provider = new OrderedAddressSuggestionProvider(
            [
                new class () implements AddressSuggestionSourceInterface {
                    public function sourceKey(): string
                    {
                        return 'legacy-suggest';
                    }

                    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
                    {
                        return [
                            new AddressSuggestionResult('Main Street, Toronto, ON', new AddressView('Main Street', 'Toronto', 'ON', 'M5H', 'CA'), 'p2'),
                            new AddressSuggestionResult('Main Street, Houston, TX', new AddressView('Main Street', 'Houston', 'TX', '77002', 'US'), 'p1'),
                        ];
                    }
                },
            ],
            new StaticAddressSuggestionSourceOrder(),
            new AddressSuggestionRanker(),
        );

        $items = $provider->suggest('Main', 'US', 1);

        self::assertCount(1, $items);
        self::assertSame('Main Street, Houston, TX', $items[0]->label());
    }
}
