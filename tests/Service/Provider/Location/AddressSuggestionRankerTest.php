<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\AddressSuggestionResult;
use App\Entity\Location\AddressView;
use App\Service\Provider\Location\AddressSuggestionRanker;
use PHPUnit\Framework\TestCase;

final class AddressSuggestionRankerTest extends TestCase
{
    public function testRankPrefersQueryAndCountryMatches(): void
    {
        $ranker = new AddressSuggestionRanker();

        $items = [
            new AddressSuggestionResult('Other Road, Toronto, ON', new AddressView('Other Road', 'Toronto', 'ON', 'M5H', 'CA'), 'ca'),
            new AddressSuggestionResult('Main Street, Houston, TX', new AddressView('Main Street', 'Houston', 'TX', '77002', 'US'), 'us'),
            new AddressSuggestionResult('Main Avenue, Berlin', new AddressView('Main Avenue', 'Berlin', 'BE', '10115', 'DE'), 'de'),
        ];

        $ranked = $ranker->rank('Main', 'US', $items);

        self::assertSame('Main Street, Houston, TX', $ranked[0]->label());
    }
}
