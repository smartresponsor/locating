<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\AddressReverseResult;
use App\Entity\Location\AddressView;
use App\Service\Provider\Location\PolicyAddressReverseSourceOrder;
use App\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface;
use PHPUnit\Framework\TestCase;

final class PolicyAddressReverseSourceOrderTest extends TestCase
{
    public function testOrderUsesQuotaAndHealthSignals(): void
    {
        $healthy = new class implements AddressReverseSourceInterface {
            public function sourceKey(): string
            {
                return 'healthy';
            }

            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\EntityInterface\Location\AddressReverseResultInterface
            {
                return new AddressReverseResult('verified', new AddressView('A', 'B', 'C', '1', 'US'), [], null, 'healthy');
            }
        };

        $blocked = new class implements AddressReverseSourceInterface {
            public function sourceKey(): string
            {
                return 'blocked';
            }

            public function reverse(float $latitude, float $longitude, ?string $countryCode = null): \App\EntityInterface\Location\AddressReverseResultInterface
            {
                return new AddressReverseResult('verified', new AddressView('A', 'B', 'C', '1', 'US'), [], null, 'blocked');
            }
        };

        $order = new PolicyAddressReverseSourceOrder(
            new class implements AddressReverseSourceHealthPolicyInterface {
                public function score(string $sourceKey): float
                {
                    return match ($sourceKey) {
                        'healthy' => 0.95,
                        'blocked' => 0.99,
                        default => 0.1,
                    };
                }
            },
            new class implements AddressReverseSourceQuotaPolicyInterface {
                public function allows(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): bool
                {
                    return 'blocked' !== $sourceKey;
                }
            },
            new class implements AddressReverseSourceCostPolicyInterface {
                public function penalty(string $sourceKey, float $latitude, float $longitude, ?string $countryCode = null): float
                {
                    return 'healthy' === $sourceKey ? 0.2 : 0.9;
                }
            },
        );

        $ordered = $order->order([$blocked, $healthy], 29.7604, -95.3698, 'US');

        self::assertCount(1, $ordered);
        self::assertSame('healthy', $ordered[0]->sourceKey());
    }
}
