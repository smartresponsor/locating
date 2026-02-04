<?php
declare(strict_types=1);

namespace Tests\Locator\Address;

use App\Entity\Locator\AddressInput;
use App\Entity\Locator\AddressStatus;
use App\Service\Locator\AddressNormalizer;
use App\Service\Locator\AddressParser;
use App\Service\Locator\AddressParserGeneric;
use App\Service\Locator\AddressPipeline;
use App\Service\Locator\AddressValidator;
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

