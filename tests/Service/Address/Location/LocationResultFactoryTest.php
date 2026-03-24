<?php

declare(strict_types=1);

namespace Tests\Service\Address\Location;

use App\Service\Address\Location\LocationResultFactory;
use PHPUnit\Framework\TestCase;

final class LocationResultFactoryTest extends TestCase
{
    public function testCreatesAppOwnedSuggestionAndReverseResults(): void
    {
        $factory = new LocationResultFactory();

        $suggestion = $factory->createSuggestionResultFromArray([
            'label' => 'Main St, Houston, TX',
            'address' => [
                'street' => 'Main St',
                'city' => 'Houston',
                'region' => 'TX',
                'postalCode' => '77002',
                'countryCode' => 'US',
            ],
            'providerKey' => 'mapbox',
        ]);

        self::assertSame('Main St, Houston, TX', $suggestion->label());
        self::assertSame('US', $suggestion->address()->toArray()['countryCode']);

        $reverse = $factory->createReverseResultFromArray([
            'status' => 'valid',
            'address' => [
                'street' => 'Main St',
                'city' => 'Houston',
                'region' => 'TX',
                'postalCode' => '77002',
                'countryCode' => 'US',
            ],
            'issues' => [],
            'geoPoint' => ['latitude' => 29.7604, 'longitude' => -95.3698],
            'providerKey' => 'here',
        ]);

        self::assertSame('valid', $reverse->status());
        self::assertSame('here', $reverse->providerKey());
    }
}
