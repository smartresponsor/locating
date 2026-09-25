<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Model\Location\AddressSuggestionResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\PolicyInterface\Provider\Location\AddressSuggestionSourceCostPolicyInterface;
use App\Locating\PolicyInterface\Provider\Location\AddressSuggestionSourceHealthPolicyInterface;
use App\Locating\Service\Provider\Location\PolicyAddressSuggestionSourceOrder;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceQuotaPolicyInterface;
use PHPUnit\Framework\TestCase;

final class PolicyAddressSuggestionSourceOrderTest extends TestCase
{
    public function testOrderPrefersHealthierEligibleSources(): void
    {
        $first = new class () implements AddressSuggestionSourceInterface {
            public function sourceKey(): string
            {
                return 'first';
            }

            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [new AddressSuggestionResult('a', new AddressView('a', '', '', '', ''), 'first')];
            }
        };
        $second = new class () implements AddressSuggestionSourceInterface {
            public function sourceKey(): string
            {
                return 'second';
            }

            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [new AddressSuggestionResult('b', new AddressView('b', '', '', '', ''), 'second')];
            }
        };

        $order = new PolicyAddressSuggestionSourceOrder(
            new class () implements AddressSuggestionSourceHealthPolicyInterface {
                public function score(string $sourceKey): float
                {
                    return 'second' === $sourceKey ? 0.9 : 0.2;
                }
            },
            new class () implements AddressSuggestionSourceQuotaPolicyInterface {
                public function allows(string $sourceKey, string $query, ?string $countryCode = null, int $limit = 5): bool
                {
                    return 'first' !== $sourceKey;
                }
            },
            new class () implements AddressSuggestionSourceCostPolicyInterface {
                public function penalty(string $sourceKey, string $query, ?string $countryCode = null, int $limit = 5): float
                {
                    return 'second' === $sourceKey ? 0.2 : 0.9;
                }
            },
        );

        $ordered = $order->order([$first, $second], 'main', 'US', 5);

        self::assertCount(1, $ordered);
        self::assertSame('second', $ordered[0]->sourceKey());
    }
}
