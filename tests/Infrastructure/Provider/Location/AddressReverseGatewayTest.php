<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Infrastructure\Provider\Location\AddressReverseGateway;
use App\Locating\InfrastructureInterface\Provider\Location\Http\AddressReverseHttpBackendInterface;
use PHPUnit\Framework\TestCase;

final class AddressReverseGatewayTest extends TestCase
{
    public function testReverseDelegatesToHttpBackend(): void
    {
        $gateway = new AddressReverseGateway(new class () implements AddressReverseHttpBackendInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
            {
                return ['address' => ['road' => 'Main St']];
            }
        });

        $payload = $gateway->reverse(29.7604, -95.3698, 'US');

        self::assertSame('Main St', $payload['address']['road']);
    }
}
