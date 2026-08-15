<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Address\Location;

use App\Locating\Model\Location\AddressInput;
use App\Locating\Service\Address\Location\AddressParser;
use PHPUnit\Framework\TestCase;

final class AddressParserTest extends TestCase
{
    public function testParseUsesStructuredDataWhenAvailable(): void
    {
        $parser = new AddressParser();
        $input = new AddressInput('ignored', [
            'street' => '123 Main St',
            'city' => 'Houston',
            'region' => 'TX',
            'postalCode' => '77001',
            'countryCode' => 'us',
        ]);

        $address = $parser->parse($input);

        self::assertSame('123 Main St', $address->street());
        self::assertSame('Houston', $address->city());
        self::assertSame('TX', $address->region());
        self::assertSame('77001', $address->postalCode());
        self::assertSame('us', $address->countryCode());
    }

    public function testParseFallsBackToRawAddressLine(): void
    {
        $parser = new AddressParser();
        $input = new AddressInput('500 Elm St, Dallas, TX, 75201, us');

        $address = $parser->parse($input);

        self::assertSame('500 Elm St', $address->street());
        self::assertSame('Dallas', $address->city());
        self::assertSame('TX', $address->region());
        self::assertSame('75201', $address->postalCode());
        self::assertSame('US', $address->countryCode());
    }
}
