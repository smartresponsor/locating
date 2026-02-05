<?php
declare(strict_types=1);

namespace Tests\Locator\Address;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\Service\Locator\AddressParser;
use Smartresponsor\Service\Locator\AddressParserGeneric;
use PHPUnit\Framework\TestCase;

final class AddressParserTest extends TestCase
{
    public function testGenericParserParsesSimpleLine(): void
    {
        $input = new AddressInput(
            'Main Street 1, Austin, TX, 78701',
            'us',
            null,
            null,
            null,
            null,
            null,
            null
        );

        $parser = new AddressParserGeneric();
        $data = $parser->parse($input);

        $this->assertSame('US', $data->countryCode());
        $this->assertSame('Main Street 1', $data->street());
    }

    public function testAddressParserDelegatesToStrategy(): void
    {
        $input = new AddressInput(
            'Main Street 1, Austin, TX, 78701',
            'US',
            null,
            null,
            null,
            null,
            null,
            null
        );

        $strategy = new AddressParserGeneric();
        $parser = new AddressParser([$strategy]);

        $data = $parser->parse($input);

        $this->assertSame('US', $data->countryCode());
        $this->assertSame('Main Street 1', $data->street());
    }
}

