<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Http\Location;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\AddressResult;
use App\Locating\Model\Location\AddressStatus;
use App\Locating\Model\Location\AddressSuggestion;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ModelInterface\Location\AddressIssueInterface;
use App\Locating\Service\Http\Location\LocationViewFactory;
use PHPUnit\Framework\TestCase;

final class LocationViewFactoryTest extends TestCase
{
    public function testCreatesSuggestionViewFromSuggestionResult(): void
    {
        $factory = new LocationViewFactory();
        $view = $factory->createSuggestionView(new AddressSuggestion('123 Main St, Houston, TX', new AddressData('123 Main St', 'Houston', 'TX', '77002', 'US'), 'mapbox'));
        self::assertSame([
            'label' => '123 Main St, Houston, TX',
            'address' => ['street' => '123 Main St', 'city' => 'Houston', 'region' => 'TX', 'postalCode' => '77002', 'countryCode' => 'US'],
            'providerKey' => 'mapbox',
        ], $view->toArray());
    }

    public function testCreatesReverseViewFromResult(): void
    {
        $factory = new LocationViewFactory();
        $issue = new class () implements AddressIssueInterface {
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

            public function toArray(): array
            {
                return [
                    'field' => $this->field(),
                    'code' => $this->code(),
                    'message' => $this->message(),
                ];
            }
        };
        $view = $factory->createReverseView(AddressResult::create(AddressStatus::VERIFIED, new AddressData('123 Main St', 'Houston', 'TX', '77002', 'US'), [$issue], new GeoPoint(29.7604, -95.3698), 'here'));
        self::assertSame([
            'status' => 'verified',
            'address' => ['street' => '123 Main St', 'city' => 'Houston', 'region' => 'TX', 'postalCode' => '77002', 'countryCode' => 'US'],
            'issues' => [['field' => 'postalCode', 'code' => 'format', 'message' => 'Postal code format mismatch.']],
            'geoPoint' => ['latitude' => 29.7604, 'longitude' => -95.3698],
            'providerKey' => 'here',
        ], $view->toArray());
    }
}
