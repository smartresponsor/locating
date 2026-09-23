<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Service\Provider\Location\AddressSuggestGateway;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestBackendInterface;
use PHPUnit\Framework\TestCase;

final class AddressSuggestGatewayTest extends TestCase
{
    public function testSuggestNormalizesProviderPayload(): void
    {
        $gateway = new AddressSuggestGateway(new class () implements AddressSuggestBackendInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [
                    ['label' => 'One', 'address' => ['street' => 'A'], 'providerKey' => 'p1'],
                ];
            }
        });

        $items = $gateway->suggest('Main', 'US', 5);

        self::assertCount(1, $items);
        self::assertSame('One', $items[0]['label']);
        self::assertSame('A', $items[0]['address']['street']);
        self::assertSame('p1', $items[0]['providerKey']);
    }
}
