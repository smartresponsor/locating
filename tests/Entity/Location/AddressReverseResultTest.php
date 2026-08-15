<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\AddressReverseResult;
use App\Locating\Model\Location\AddressView;
use PHPUnit\Framework\TestCase;

final class AddressReverseResultTest extends TestCase
{
    public function testExposesAppOwnedReverseResultState(): void
    {
        $item = new AddressReverseResult(
            'valid',
            new AddressView('Main St', 'Houston', 'TX', '77002', 'US'),
            [['field' => 'postalCode', 'code' => 'normalized', 'message' => 'Normalized.']],
            ['latitude' => 29.7604, 'longitude' => -95.3698],
            'here',
        );

        self::assertSame('valid', $item->status());
        self::assertSame('US', $item->address()?->toArray()['countryCode']);
        self::assertSame('here', $item->providerKey());
    }
}
