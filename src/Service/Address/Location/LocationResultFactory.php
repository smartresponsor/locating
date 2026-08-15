<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\Model\Location\AddressReverseResult;
use App\Locating\Model\Location\AddressSuggestionResult;
use App\Locating\Model\Location\AddressView;
use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ServiceInterface\Address\Location\LocationResultFactoryInterface;

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
