<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\FactoryInterface\Http\Location;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\ModelInterface\Location\AddressReverseViewInterface;
use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ModelInterface\Location\AddressSuggestionViewInterface;

interface LocationViewFactoryInterface
{
    public function createSuggestionView(AddressSuggestionResultInterface $suggestion): AddressSuggestionViewInterface;

    public function createReverseView(AddressReverseResultInterface $result): AddressReverseViewInterface;
}
