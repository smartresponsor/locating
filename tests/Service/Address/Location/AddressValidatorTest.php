<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Address\Location;

use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\Service\Address\Location\AddressValidator;
use PHPUnit\Framework\TestCase;

final class AddressValidatorTest extends TestCase
{
    public function testValidateReturnsVerifiedForCompleteAddress(): void
    {
        $validator = new AddressValidator();
        $address = new AddressView('123 Main St', 'Houston', 'TX', '77001', 'US');

        $result = $validator->validate($address);

        self::assertSame(AddressPipelineResult::STATUS_VERIFIED, $result->status());
        self::assertSame([], $result->issues());
        self::assertNotNull($result->address());
    }

    public function testValidateReturnsRejectedForMissingStreetAndCity(): void
    {
        $validator = new AddressValidator();
        $address = new AddressView('', '', 'TX', '77001', 'US');

        $result = $validator->validate($address);

        self::assertSame(AddressPipelineResult::STATUS_REJECTED, $result->status());
        self::assertNull($result->address());
        self::assertCount(2, $result->issues());
    }
}
