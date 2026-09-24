<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Provider\Location;

use App\Locating\FactoryInterface\Address\Location\LocationResultFactoryInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestGatewayInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;

final class AddressSuggestionProvider implements AddressSuggestionSourceInterface
{
    public function sourceKey(): string
    {
        return 'suggest';
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
