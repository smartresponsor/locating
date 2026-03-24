<?php

declare(strict_types=1);

namespace Tests\Service\Address\Location;

use App\Entity\Location\AddressInput;
use App\Entity\Location\AddressPipelineResult;
use App\Service\Address\Location\AddressNormalizer;
use App\Service\Address\Location\AddressParser;
use App\Service\Address\Location\AddressPipeline;
use App\Service\Address\Location\AddressValidator;
use PHPUnit\Framework\TestCase;

final class AddressPipelineTest extends TestCase
{
    public function testProcessBuildsNormalizedVerifiedAddressResult(): void
    {
        $pipeline = new AddressPipeline(
            new AddressParser(),
            new AddressNormalizer(),
            new AddressValidator(),
        );

        $result = $pipeline->process(new AddressInput(' 123 Main St , Houston , TX , 77001 , us '));

        self::assertSame(AddressPipelineResult::STATUS_VERIFIED, $result->status());
        self::assertNotNull($result->address());
        self::assertSame('123 Main St', $result->address()?->street());
        self::assertSame('US', $result->address()?->countryCode());
    }
}
