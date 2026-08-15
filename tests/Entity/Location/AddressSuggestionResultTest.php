<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\AddressSuggestionResult;
use App\Locating\Model\Location\AddressView;
use PHPUnit\Framework\TestCase;

final class AddressSuggestionResultTest extends TestCase
{
    public function testExposesAppOwnedSuggestionResultState(): void
    {
        $item = new AddressSuggestionResult(
            'Main St, Houston, TX',
            new AddressView('Main St', 'Houston', 'TX', '77002', 'US'),
            'mapbox',
        );

        self::assertSame('Main St, Houston, TX', $item->label());
        self::assertSame('Houston', $item->address()->toArray()['city']);
        self::assertSame('mapbox', $item->providerKey());
    }
}
