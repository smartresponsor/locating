<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Infrastructure\Provider\Location\LegacyAddressSuggestGateway;
use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressSuggestion;

final class LegacyAddressSuggestGatewayTest extends TestCase
{
    public function testSuggestNormalizesLegacyProviderPayload(): void
    {
        $gateway = new LegacyAddressSuggestGateway([
            new class implements AddressSuggestProviderInterface {
                public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
                {
                    return [
                        new AddressSuggestion('One', AddressData::fromArray(['street' => 'A', 'city' => 'X', 'region' => 'TX', 'postalCode' => '1', 'countryCode' => 'US']), 'p1'),
                    ];
                }
            },
        ]);

        $items = $gateway->suggest('Main', 'US', 5);

        self::assertCount(1, $items);
        self::assertSame('One', $items[0]['label']);
        self::assertSame('A', $items[0]['address']['street']);
        self::assertSame('p1', $items[0]['providerKey']);
    }
}
