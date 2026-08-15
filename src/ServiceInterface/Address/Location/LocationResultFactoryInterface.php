<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Address\Location;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;

interface LocationResultFactoryInterface
{
    /**
     * @param array{label:string,address:array<string,string>,providerKey:string} $suggestion
     */
    public function createSuggestionResultFromArray(array $suggestion): AddressSuggestionResultInterface;

    /**
     * @param array{status:string,address:?array<string,string>,issues:list<array{field:string,code:string,message:string}>,geoPoint:?array{latitude:float,longitude:float},providerKey:?string} $result
     */
    public function createReverseResultFromArray(array $result): AddressReverseResultInterface;
}
