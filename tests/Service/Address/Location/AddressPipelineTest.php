<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Address\Location;

use App\Locating\Model\Location\AddressInput;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\Normalizer\Address\Location\AddressNormalizer;
use App\Locating\Service\Address\Location\AddressParser;
use App\Locating\Service\Address\Location\AddressPipeline;
use App\Locating\Service\Address\Location\AddressValidator;
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
        $address = $result->address();
        self::assertNotNull($address);
        self::assertSame('123 Main St', $address->street());
        self::assertSame('US', $address->countryCode());
    }
}
