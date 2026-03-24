<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\AddressView;
use PHPUnit\Framework\TestCase;

final class AddressViewTest extends TestCase
{
    public function testBuildsNormalizedPayloadFromArray(): void
    {
        $view = AddressView::fromArray([
            'street' => '123 Main St',
            'city' => 'Houston',
            'region' => 'TX',
            'postalCode' => '77002',
            'countryCode' => 'US',
        ]);

        self::assertSame([
            'street' => '123 Main St',
            'city' => 'Houston',
            'region' => 'TX',
            'postalCode' => '77002',
            'countryCode' => 'US',
        ], $view->toArray());
    }
}
