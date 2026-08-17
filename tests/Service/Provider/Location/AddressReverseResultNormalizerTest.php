<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Model\Location\AddressReverseResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Provider\Location\AddressReverseResultNormalizer;
use PHPUnit\Framework\TestCase;

final class AddressReverseResultNormalizerTest extends TestCase
{
    public function testNormalizeUppercasesCountryCodeAndBackfillsGeoPointAndProviderKey(): void
    {
        $normalizer = new AddressReverseResultNormalizer();
        $result = new AddressReverseResult(
            'verified',
            new AddressView(' Main St 10 ', ' Houston ', ' Texas ', '77001', 'us'),
            [],
            null,
            null,
        );

        $normalized = $normalizer->normalize($result, 29.7604, -95.3698, 'US');

        $address = $normalized->address();
        self::assertNotNull($address);
        self::assertSame('Main St 10', $address->street());
        self::assertSame('Houston', $address->city());
        self::assertSame('US', $address->countryCode());
        self::assertSame(['latitude' => 29.7604, 'longitude' => -95.3698], $normalized->geoPoint());
        self::assertSame('reverse', $normalized->providerKey());
    }
}
