<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Address\Location;

use App\Entity\Location\AddressReverseResult;
use App\Entity\Location\AddressSuggestionResult;
use App\Entity\Location\AddressView;
use App\EntityInterface\Location\AddressReverseResultInterface;
use App\EntityInterface\Location\AddressSuggestionResultInterface;
use App\ServiceInterface\Address\Location\LocationResultFactoryInterface;

final class LocationResultFactory implements LocationResultFactoryInterface
{
    public function createSuggestionResultFromArray(array $suggestion): AddressSuggestionResultInterface
    {
        return new AddressSuggestionResult(
            (string) $suggestion['label'],
            AddressView::fromArray($suggestion['address']),
            (string) $suggestion['providerKey'],
        );
    }

    public function createReverseResultFromArray(array $result): AddressReverseResultInterface
    {
        return new AddressReverseResult(
            (string) $result['status'],
            is_array($result['address'] ?? null) ? AddressView::fromArray($result['address']) : null,
            array_values($result['issues'] ?? []),
            is_array($result['geoPoint'] ?? null) ? $result['geoPoint'] : null,
            isset($result['providerKey']) ? (string) $result['providerKey'] : null,
        );
    }
}
