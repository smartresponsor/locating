<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\AddressReverseController;
use App\Entity\Location\AddressReverseView;
use App\Entity\Location\AddressView;
use App\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;
use App\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class AddressReverseControllerTest extends TestCase
{
    public function testReturnsBadRequestWhenCoordinatesAreMissing(): void
    {
        $service = new class implements LocationAddressReverseServiceInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseView
            {
                return new AddressReverseView('valid', AddressView::fromArray([]), [], null, null);
            }
        };

        $controller = new AddressReverseController($service);
        $response = $controller(new Request());

        self::assertSame(400, $response->getStatusCode());
        self::assertStringContainsString('lat and lon', (string) $response->getContent());
    }

    public function testReturnsQuotaExceededWhenGuardBlocksReverse(): void
    {
        $service = new class implements LocationAddressReverseServiceInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseView
            {
                return new AddressReverseView('valid', AddressView::fromArray([]), [], null, null);
            }
        };

        $guard = new class implements LocationQuotaGuardInterface {
            public function isAllowed(string $operation): bool
            {
                TestCase::assertSame(self::OPERATION_REVERSE, $operation);

                return false;
            }
        };

        $controller = new AddressReverseController($service, $guard);
        $response = $controller(new Request(['lat' => '29.7604', 'lon' => '-95.3698']));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"quotaExceeded":true}', (string) $response->getContent());
    }
}
