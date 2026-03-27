<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Http\Location;

use App\Entity\Location\AddressReverseView;
use App\Entity\Location\AddressSuggestionView;
use App\EntityInterface\Location\AddressReverseResultInterface;
use App\EntityInterface\Location\AddressReverseViewInterface;
use App\EntityInterface\Location\AddressSuggestionResultInterface;
use App\EntityInterface\Location\AddressSuggestionViewInterface;
use App\ServiceInterface\Http\Location\LocationViewFactoryInterface;

final class LocationViewFactory implements LocationViewFactoryInterface
{
    public function createSuggestionView(AddressSuggestionResultInterface $suggestion): AddressSuggestionViewInterface
    {
        return new AddressSuggestionView(
            $suggestion->label(),
            $suggestion->address(),
            $suggestion->providerKey(),
        );
    }

    public function createReverseView(AddressReverseResultInterface $result): AddressReverseViewInterface
    {
        return new AddressReverseView(
            $result->status(),
            $result->address(),
            $result->issues(),
            $result->geoPoint(),
            $result->providerKey(),
        );
    }
}
