<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\InfrastructureInterface\Provider\Location\AddressSuggestGatewayInterface;
use App\ServiceInterface\Address\Location\LocationResultFactoryInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;

final class LegacyAddressSuggestionProvider implements AddressSuggestionSourceInterface
{
    public function sourceKey(): string
    {
        return 'legacy-suggest';
    }

    public function __construct(
        private readonly AddressSuggestGatewayInterface $gateway,
        private readonly LocationResultFactoryInterface $resultFactory,
    ) {
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        if ('' === $query || $limit <= 0) {
            return [];
        }

        $items = [];

        foreach ($this->gateway->suggest($query, $countryCode, $limit) as $suggestion) {
            $items[] = $this->resultFactory->createSuggestionResultFromArray($suggestion);

            if (count($items) >= $limit) {
                return $items;
            }
        }

        return $items;
    }
}
