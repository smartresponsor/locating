<?php

declare(strict_types=1);

namespace Tests\Controller\Http;

use App\Controller\Http\Location\AddressSuggestController;
use App\Entity\Location\AddressSuggestionView;
use App\Entity\Location\AddressView;
use App\ServiceInterface\Http\Location\AddressSuggestServiceInterface;
use App\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class AddressSuggestControllerTest extends TestCase
{
    public function testReturnsEmptyItemsForBlankQuery(): void
    {
        $service = new class implements AddressSuggestServiceInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                return [new AddressSuggestionView('should not run', AddressView::fromArray([]), null)];
            }
        };

        $controller = new AddressSuggestController($service);
        $response = $controller(new Request(['query' => '   ']));

        self::assertSame('{"items":[]}', (string) $response->getContent());
    }

    public function testCapsLimitAndReturnsSerializedItems(): void
    {
        $service = new class implements AddressSuggestServiceInterface {
            public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
            {
                TestCase::assertSame('Main', $query);
                TestCase::assertSame('US', $countryCode);
                TestCase::assertSame(20, $limit);

                return [new AddressSuggestionView(
                    'Main St, Houston, TX',
                    AddressView::fromArray([
                        'street' => 'Main St',
                        'city' => 'Houston',
                        'region' => 'TX',
                        'postalCode' => '77002',
                        'countryCode' => 'US',
                    ]),
                    'mapbox',
                )];
            }
        };

        $guard = new class implements LocationQuotaGuardInterface {
            public function isAllowed(string $operation): bool
            {
                TestCase::assertSame(self::OPERATION_SUGGEST, $operation);

                return true;
            }
        };

        $controller = new AddressSuggestController($service, $guard);
        $response = $controller(new Request(['query' => 'Main', 'country' => 'US', 'limit' => '999']));

        self::assertSame(200, $response->getStatusCode());
        self::assertSame('{"items":[{"label":"Main St, Houston, TX","address":{"street":"Main St","city":"Houston","region":"TX","postalCode":"77002","countryCode":"US"},"providerKey":"mapbox"}]}', (string) $response->getContent());
    }
}
