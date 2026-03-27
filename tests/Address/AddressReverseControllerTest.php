<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Address;

use App\Controller\AddressReverseController;
use App\Entity\AddressData;
use App\Entity\AddressResult;
use App\Entity\AddressStatus;
use App\Entity\GeoPoint;
use App\ServiceInterface\AddressQuotaGuardInterface;
use App\ServiceInterface\AddressReverseInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class AddressReverseControllerTest extends TestCase
{
    public function testMissingCoordinateReturnsBadRequest(): void
    {
        $service = $this->createStub(AddressReverseInterface::class);
        $controller = new AddressReverseController($service, null);

        $request = new Request();

        $response = $controller($request);

        self::assertSame(400, $response->getStatusCode());
    }

    public function testQuotaExceededReturnsSoftFlag(): void
    {
        $service = $this->createStub(AddressReverseInterface::class);

        $quotaGuard = new class () implements AddressQuotaGuardInterface {
            public function isAllowed(string $operation): bool
            {
                return false;
            }
        };

        $controller = new AddressReverseController($service, $quotaGuard);

        $request = new Request(['lat' => '10.0', 'lon' => '20.0']);

        $response = $controller($request);

        self::assertSame(200, $response->getStatusCode());
        $decoded = json_decode((string)$response->getContent(), true);
        self::assertIsArray($decoded);
        self::assertArrayHasKey('quotaExceeded', $decoded);
        self::assertTrue($decoded['quotaExceeded']);
    }

    public function testSuccessfulReverseReturnsPayload(): void
    {
        $service = new class () implements AddressReverseInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressResult
            {
                $data = AddressData::fromArray([
                    'street' => 'Test Street 1',
                    'city' => 'TestCity',
                    'region' => 'TX',
                    'postalCode' => '12345',
                    'country' => 'United States',
                    'countryCode' => 'US',
                ]);

                $point = new GeoPoint($latitude, $longitude);

                return AddressResult::create(
                    AddressStatus::VERIFIED,
                    $data,
                    [],
                    $point,
                    'test-provider'
                );
            }
        };

        $controller = new AddressReverseController($service, null);

        $request = new Request(['lat' => '29.7604', 'lon' => '-95.3698', 'country' => 'US']);

        $response = $controller($request);

        self::assertSame(200, $response->getStatusCode());
        $decoded = json_decode((string)$response->getContent(), true);
        self::assertIsArray($decoded);
        self::assertArrayHasKey('address', $decoded);
        self::assertSame('TestCity', $decoded['address']['city']);
    }
}
