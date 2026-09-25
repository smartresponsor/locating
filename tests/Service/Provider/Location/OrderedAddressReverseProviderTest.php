<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Model\Location\AddressReverseResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\Normalizer\Provider\Location\AddressReverseResultNormalizer;
use App\Locating\Provider\Location\OrderedAddressReverseProvider;
use App\Locating\Service\Provider\Location\PolicyAddressReverseSourceOrder;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceInterface;
use PHPUnit\Framework\TestCase;

final class OrderedAddressReverseProviderTest extends TestCase
{
    public function testReverseUsesOrderedSourceAndNormalizesResult(): void
    {
        $source = new class () implements AddressReverseSourceInterface {
            public function sourceKey(): string
            {
                return 'reverse-a';
            }

            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\Locating\ModelInterface\Location\AddressReverseResultInterface
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
                new class () implements \App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface {
                    public function score(string $sourceKey): float
                    {
                        return 0.9;
                    }
                },
                new class () implements \App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface {
                    public function allows(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): bool
                    {
                        return true;
                    }
                },
                new class () implements \App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface {
                    public function penalty(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): float
                    {
                        return 0.0;
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
