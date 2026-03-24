<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Infrastructure\Provider\Location\LegacyAddressReverseGateway;
use PHPUnit\Framework\TestCase;

final class LegacyAddressReverseGatewayTest extends TestCase
{
    public function testReverseDelegatesToLegacyClient(): void
    {
        $gateway = new LegacyAddressReverseGateway(new class implements ReverseHttpClientInterface {
            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): array
            {
                return ['address' => ['road' => 'Main St']];
            }
        });

        $payload = $gateway->reverse(29.7604, -95.3698, 'US');

        self::assertSame('Main St', $payload['address']['road']);
    }
}
