<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Http\Location;

use App\Locating\Model\Location\AddressReverseView;
use App\Locating\Model\Location\AddressSuggestionView;
use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\ModelInterface\Location\AddressReverseViewInterface;
use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ModelInterface\Location\AddressSuggestionViewInterface;
use App\Locating\ServiceInterface\Http\Location\LocationViewFactoryInterface;

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
