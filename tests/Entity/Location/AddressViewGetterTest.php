<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\AddressView;
use PHPUnit\Framework\TestCase;

final class AddressViewGetterTest extends TestCase
{
    public function testTypedGettersExposeAddressFields(): void
    {
        $view = new AddressView('123 Main St', 'Houston', 'TX', '77001', 'US');

        self::assertSame('123 Main St', $view->street());
        self::assertSame('Houston', $view->city());
        self::assertSame('TX', $view->region());
        self::assertSame('77001', $view->postalCode());
        self::assertSame('US', $view->countryCode());
    }
}
