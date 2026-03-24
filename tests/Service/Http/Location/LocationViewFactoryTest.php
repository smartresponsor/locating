<?php

declare(strict_types=1);

namespace Tests\Service\Http\Location;

use App\Bridge\Legacy\Entity\Location\AddressValidationIssueLegacyInterface;
use App\Service\Http\Location\LocationViewFactory;
use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressResult;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\Entity\Locator\AddressSuggestion;
use Smartresponsor\Entity\Locator\GeoPoint;

final class LocationViewFactoryTest extends TestCase
{
    public function testCreatesSuggestionViewFromLegacySuggestion(): void
    {
        $factory = new LocationViewFactory();
        $view = $factory->createSuggestionView(new AddressSuggestion('123 Main St, Houston, TX', new AddressData('123 Main St', 'Houston', 'TX', '77002', 'US'), 'mapbox'));
        self::assertSame([
            'label' => '123 Main St, Houston, TX',
            'address' => ['street' => '123 Main St', 'city' => 'Houston', 'region' => 'TX', 'postalCode' => '77002', 'countryCode' => 'US'],
            'providerKey' => 'mapbox',
        ], $view->toArray());
    }

    public function testCreatesReverseViewFromLegacyResult(): void
    {
        $factory = new LocationViewFactory();
        $issue = new class implements AddressValidationIssueLegacyInterface {
            public function field(): string
            {
                return 'postalCode';
            }

            public function code(): string
            {
                return 'format';
            }

            public function message(): string
            {
                return 'Postal code format mismatch.';
            }
        };
        $view = $factory->createReverseView(AddressResult::create(AddressStatus::VALID, new AddressData('123 Main St', 'Houston', 'TX', '77002', 'US'), [$issue], new GeoPoint(29.7604, -95.3698), 'here'));
        self::assertSame([
            'status' => 'valid',
            'address' => ['street' => '123 Main St', 'city' => 'Houston', 'region' => 'TX', 'postalCode' => '77002', 'countryCode' => 'US'],
            'issues' => [['field' => 'postalCode', 'code' => 'format', 'message' => 'Postal code format mismatch.']],
            'geoPoint' => ['latitude' => 29.7604, 'longitude' => -95.3698],
            'providerKey' => 'here',
        ], $view->toArray());
    }
}
