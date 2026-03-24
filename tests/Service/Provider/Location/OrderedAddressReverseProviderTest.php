<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\AddressReverseResult;
use App\Entity\Location\AddressView;
use App\Service\Provider\Location\AddressReverseResultNormalizer;
use App\Service\Provider\Location\OrderedAddressReverseProvider;
use App\Service\Provider\Location\PolicyAddressReverseSourceOrder;
use App\ServiceInterface\Provider\Location\AddressReverseSourceInterface;
use PHPUnit\Framework\TestCase;

final class OrderedAddressReverseProviderTest extends TestCase
{
    public function testReverseUsesOrderedSourceAndNormalizesResult(): void
    {
        $source = new class implements AddressReverseSourceInterface {
            public function sourceKey(): string
            {
                return 'reverse-a';
            }

            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\EntityInterface\Location\AddressReverseResultInterface
            {
                return new AddressReverseResult(
                    'verified',
                    new AddressView(' Main St 10 ', ' Houston ', ' Texas ', '77001', 'us'),
                    [],
                    null,
                    '',
                );
            }
        };

        $provider = new OrderedAddressReverseProvider(
            [$source],
            new PolicyAddressReverseSourceOrder(
                new class implements \App\ServiceInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface {
                    public function score(string $sourceKey): float
                    {
                        return 0.9;
                    }
                },
                new class implements \App\ServiceInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface {
                    public function allows(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): bool
                    {
                        return true;
                    }
                },
            ),
            new AddressReverseResultNormalizer(),
        );
        $result = $provider->reverse(29.7604, -95.3698, 'US');

        self::assertSame('verified', $result->status());
        self::assertSame('US', $result->address()?->countryCode());
        self::assertSame(['latitude' => 29.7604, 'longitude' => -95.3698], $result->geoPoint());
        self::assertSame('reverse', $result->providerKey());
    }
}
