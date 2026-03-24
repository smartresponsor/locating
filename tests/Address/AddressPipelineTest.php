<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Tests\Address;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressStatus;
use Smartresponsor\Service\AddressNormalizer;
use Smartresponsor\Service\AddressParser;
use Smartresponsor\Service\AddressParserGeneric;
use Smartresponsor\Service\AddressPipeline;
use Smartresponsor\Service\AddressValidator;
use PHPUnit\Framework\TestCase;

final class AddressPipelineTest extends TestCase
{
    public function testPipelineProducesVerifiedResultForCompleteAddress(): void
    {
        $input = new AddressInput(
            'Main Street 1, Austin, TX, 78701, US',
            'US',
            'TX',
            'Austin',
            '78701',
            'Main Street 1',
            '1',
            null
        );

        $parser = new AddressParser([new AddressParserGeneric()]);
        $normalizer = new AddressNormalizer();
        $validator = new AddressValidator();

        $pipeline = new AddressPipeline($parser, $normalizer, $validator);

        $result = $pipeline->handle($input);

        $this->assertSame(AddressStatus::VERIFIED, $result->status());
        $this->assertSame('US', $result->componentValue('countryCode'));
        $this->assertSame('Main Street 1', $result->componentValue('street'));

        $normalizedLine = $result->normalizedLine();
        $this->assertNotNull($normalizedLine);
        $this->assertStringContainsString('Main Street 1', $normalizedLine);
        $this->assertStringContainsString('Austin', $normalizedLine);
    }
}

