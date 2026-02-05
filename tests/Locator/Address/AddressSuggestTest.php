<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

        namespace Smartresponsor\Tests\Locator\Address;

        use Smartresponsor\Entity\Locator\AddressData;
        use Smartresponsor\Entity\Locator\AddressSuggestion;
        use Smartresponsor\Service\Locator\AddressSuggest;
        use Smartresponsor\InfrastructureInterface\Locator\AddressSuggestProviderInterface;
        use PHPUnit\Framework\TestCase;

        final class AddressSuggestTest extends TestCase
        {
            public function testServiceUsesProvidersAndRespectsLimit(): void
            {
                $provider = new class() implements AddressSuggestProviderInterface {
                    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
                    {
                        $data = new AddressData('Main Street 1', 'City', 'Region', '12345', 'US');

                        return [
                            new AddressSuggestion($query . ' 1', $data, 'test'),
                            new AddressSuggestion($query . ' 2', $data, 'test'),
                        ];
                    }
                };

                $service = new AddressSuggest([$provider]);

                $items = $service->suggest('foo', 'US', 1);

                self.assertCount(1, $items);
                self.assertSame('foo 1', $items[0]->label());
            }
        }
