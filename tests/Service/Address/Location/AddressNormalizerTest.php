<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Address\Location;

use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Address\Location\AddressNormalizer;
use PHPUnit\Framework\TestCase;

final class AddressNormalizerTest extends TestCase
{
    public function testNormalizeTrimsWhitespaceAndUppercasesCountryCode(): void
    {
        $normalizer = new AddressNormalizer();
        $address = new AddressView('  123   Main St  ', ' Houston ', ' TX ', ' 77001 ', ' us ');

        $normalized = $normalizer->normalize($address);

        self::assertSame('123 Main St', $normalized->street());
        self::assertSame('Houston', $normalized->city());
        self::assertSame('TX', $normalized->region());
        self::assertSame('77001', $normalized->postalCode());
        self::assertSame('US', $normalized->countryCode());
    }
}
