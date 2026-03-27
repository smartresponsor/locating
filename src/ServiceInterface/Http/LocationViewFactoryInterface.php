<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Http\Location;

use App\EntityInterface\Location\AddressReverseResultInterface;
use App\EntityInterface\Location\AddressReverseViewInterface;
use App\EntityInterface\Location\AddressSuggestionResultInterface;
use App\EntityInterface\Location\AddressSuggestionViewInterface;

interface LocationViewFactoryInterface
{
    public function createSuggestionView(AddressSuggestionResultInterface $suggestion): AddressSuggestionViewInterface;

    public function createReverseView(AddressReverseResultInterface $result): AddressReverseViewInterface;
}
