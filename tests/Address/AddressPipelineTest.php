<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Tests\Address;

use App\Entity\AddressInput;
use App\Entity\AddressStatus;
use App\Service\AddressNormalizer;
use App\Service\AddressParser;
use App\Service\AddressParserGeneric;
use App\Service\AddressPipeline;
use App\Service\AddressValidator;
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
